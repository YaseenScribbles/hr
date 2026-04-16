<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Roster;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userCompanyIds = Auth::user()->companies->pluck('id')->toArray();
        $month = (int) $request->query('month', Carbon::now()->month);
        $year = (int) $request->query('year', Carbon::now()->year);
        $selectedDesignation = (int) $request->query('designation_id', 0);

        $designations = Designation::select('id', 'name')
            ->whereIn('company_id', $userCompanyIds)
            ->orderBy('name')
            ->get();

        $employees = Employee::select('id', 'name', 'des_id')
            ->with('designation:id,name')
            ->whereIn('company_id', $userCompanyIds)
            ->when($selectedDesignation, function ($query) use ($selectedDesignation) {
                $query->where('des_id', $selectedDesignation);
            })
            ->orderBy('name')
            ->get();

        $rosters = Roster::with(['employee', 'shift'])
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->whereIn('employee_id', $employees->pluck('id')->toArray())
            ->get();

        // Create attendance records for holidays from roster
        foreach ($rosters as $roster) {
            if ($roster->shift && $roster->shift->code === 'WH') {
                Attendance::firstOrCreate([
                    'employee_id' => $roster->employee_id,
                    'date' => $roster->date,
                ], [
                    'status' => 'WH',
                    'remarks' => 'Holiday',
                    'shift_id' => $roster->shift_id,
                ]);
            }
        }

        $attendances = Attendance::with(['employee', 'shift'])
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $monthDays = collect(range(1, Carbon::create($year, $month, 1)->daysInMonth()))
            ->map(function ($day) use ($year, $month) {
                $date = Carbon::create($year, $month, $day);

                return [
                    'day' => $day,
                    'weekday' => $date->format('D'),
                    'weekday_index' => (int) $date->format('w'),
                ];
            });

        $summary = $employees->map(function ($employee) use ($attendances) {
            $employeeAttendances = $attendances->where('employee_id', $employee->id);
            $statusCounts = $employeeAttendances->countBy('status')->all();

            return [
                'id' => $employee->id,
                'name' => $employee->name,
                'designation_id' => $employee->des_id,
                'designation' => optional($employee->designation)->name,
                'total_days' => $employeeAttendances->count(),
                'present_days' => $statusCounts['X'] ?? 0,
                'absent_days' => $statusCounts['A'] ?? 0,
                'first_half_absent' => $statusCounts['A/'] ?? 0,
                'second_half_absent' => $statusCounts['/A'] ?? 0,
                'holiday_days' => $statusCounts['WH'] ?? 0,
            ];
        });

        return inertia('Attendance', [
            'attendances' => $attendances,
            'employees' => $employees,
            'summary' => $summary,
            'designations' => $designations,
            'selected_designation' => $selectedDesignation,
            'month_days' => $monthDays,
            'selected_month' => $month,
            'selected_year' => $year,
            'rosters' => $rosters,
        ]);
    }

    /**
     * Store a single attendance entry or bulk update attendance for an employee-month.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
            'assignments' => 'required|array',
            'assignments.*.day' => 'required_with:assignments|integer|min:1|max:31',
            'assignments.*.status' => ['nullable', 'string', Rule::in(['X', 'A', '/A', 'A/', 'WH'])],
            'employee_id' => 'nullable|integer|exists:employees,id',
            'employee_ids' => 'nullable|array',
            'employee_ids.*' => 'integer|exists:employees,id',
        ]);

        $employeeIds = $validated['employee_ids'] ?? ([]);

        if (!empty($validated['employee_id'])) {
            $employeeIds[] = $validated['employee_id'];
        }

        if (empty($employeeIds)) {
            return back()->with('toast', ['type' => 'error', 'message' => 'Please select at least one employee.']);
        }

        $month = $validated['month'];
        $year = $validated['year'];
        $assignments = $validated['assignments'];

        try {
            DB::beginTransaction();

            Attendance::whereIn('employee_id', $employeeIds)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->delete();

            foreach ($employeeIds as $employeeId) {
                foreach ($assignments as $assignment) {
                    if (empty($assignment['status'])) {
                        continue;
                    }

                    $date = Carbon::create($year, $month, $assignment['day'])->toDateString();

                    $roster = Roster::with('shift')->where('employee_id', $employeeId)->where('date', $date)->first();

                    $logIn = null;
                    $lunchOut = null;
                    $lunchIn = null;
                    $logOut = null;
                    $actualHours = null;
                    $totalHours = null;

                    if ($roster && $roster->shift) {
                        $status = $assignment['status'];
                        Log::info("Generating attendance for employee_id: $employeeId, date: $date, status: $status, shift: {$roster->shift->code}");
                        if ($status === 'X') {
                            $logIn = $this->generateRandomTime($roster->shift->login_min, $roster->shift->login_max);
                            $lunchOut = $this->generateRandomTime($roster->shift->lunch_out_min, $roster->shift->lunch_out_max);
                            $lunchIn = $this->generateRandomTime($roster->shift->lunch_in_min, $roster->shift->lunch_in_max);
                            $logOut = $this->generateRandomTime($roster->shift->logout_min, $roster->shift->logout_max);
                            $actualHours = $this->calculateActualHours($logIn, $lunchOut, $lunchIn, $logOut);
                            $totalHours = $actualHours; // same for now
                        } elseif ($status === '/A') {
                            $logIn = $this->generateRandomTime($roster->shift->login_min, $roster->shift->login_max);
                            $logOut = $this->generateRandomTime($roster->shift->lunch_out_min, $roster->shift->lunch_out_max);
                            $actualHours = $this->calculateActualHours($logIn, null, null, $logOut, false);
                            if ($actualHours) {
                                $parts = explode(':', $actualHours);
                                $totalMinutes = intval($parts[0]) * 60 + intval($parts[1]) - 15;
                                if ($totalMinutes < 0) {
                                    $totalMinutes = 0;
                                }
                                $actualHours = sprintf('%02d:%02d:00', intdiv($totalMinutes, 60), $totalMinutes % 60);
                            }
                            $totalHours = $actualHours;
                        } elseif ($status === 'A/') {
                            $logIn = $this->generateRandomTime($roster->shift->lunch_in_min, $roster->shift->lunch_in_max);
                            $logOut = $this->generateRandomTime($roster->shift->logout_min, $roster->shift->logout_max);
                            $actualHours = $this->calculateActualHours($logIn, null, null, $logOut, false);
                            if ($actualHours) {
                                $parts = explode(':', $actualHours);
                                $totalMinutes = intval($parts[0]) * 60 + intval($parts[1]) - 15;
                                if ($totalMinutes < 0) {
                                    $totalMinutes = 0;
                                }
                                $actualHours = sprintf('%02d:%02d:00', intdiv($totalMinutes, 60), $totalMinutes % 60);
                            }
                            $totalHours = $actualHours;
                        }
                    }

                    Attendance::create([
                        'employee_id' => $employeeId,
                        'date' => $date,
                        'status' => $assignment['status'],
                        'remarks' => $this->remarkForStatus($assignment['status']),
                        'shift_id' => $roster?->shift_id,
                        'log_in' => $logIn,
                        'lunch_out' => $lunchOut,
                        'lunch_in' => $lunchIn,
                        'log_out' => $logOut,
                        'actual_hours' => $actualHours,
                        'total_hours' => $totalHours,
                    ]);
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return back()->with('toast', ['type' => 'error', 'message' => 'An error occurred while saving attendance. Please try again.']);
        }

        return back()->with('toast', ['type' => 'success', 'message' => 'Attendance updated successfully.']);
    }

    /**
     * Delete selected employee attendance for the selected month.
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'integer|exists:employees,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
        ]);

        Attendance::whereIn('employee_id', $validated['employee_ids'])
            ->whereMonth('date', $validated['month'])
            ->whereYear('date', $validated['year'])
            ->delete();

        return back()->with('toast', ['type' => 'success', 'message' => 'Attendance month deleted successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'status' => ['nullable', 'string', Rule::in(['X', 'A', '/A', 'A/', 'WH'])],
        ]);

        $attendance->update([
            'employee_id' => $validated['employee_id'],
            'date' => $validated['date'],
            'status' => $validated['status'],
            'remarks' => $this->remarkForStatus($validated['status'] ?? ''),
        ]);

        return back()->with('toast', ['type' => 'success', 'message' => 'Attendance updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return back()->with('toast', ['type' => 'success', 'message' => 'Attendance deleted successfully.']);
    }

    protected function remarkForStatus(?string $status): ?string
    {
        return match ($status) {
            'X' => 'Present',
            'A' => 'Absent',
            'A/' => 'First half absent',
            '/A' => 'Second half absent',
            'WH' => 'Holiday',
            default => null,
        };
    }

    protected function generateRandomTime(?string $minTime, ?string $maxTime): ?string
    {
        if (!$minTime || !$maxTime) {
            return null;
        }

        $min = Carbon::createFromTimeString($minTime);
        $max = Carbon::createFromTimeString($maxTime);

        if ($max->lessThan($min)) {
            $max->addDay();
        }

        $diffInSeconds = abs($max->diffInSeconds($min));
        if ($diffInSeconds === 0) {
            return $min->format('H:i:s');
        }

        $randomSeconds = random_int(0, $diffInSeconds);

        return $min->copy()->addSeconds($randomSeconds)->format('H:i:s');
    }

    protected function calculateActualHours(?string $logIn, ?string $lunchOut, ?string $lunchIn, ?string $logOut, bool $hasLunch = true): ?string
    {
        if (!$logIn || !$logOut) {
            return null;
        }

        $logInTime = Carbon::createFromTimeString($logIn);
        $logOutTime = Carbon::createFromTimeString($logOut);

        if (!$hasLunch) {
            $minutes = $logInTime->diffInMinutes($logOutTime, false);
            if ($minutes <= 0) {
                return null;
            }

            return sprintf('%02d:%02d:00', intdiv($minutes, 60), $minutes % 60);
        }

        if (!$lunchOut || !$lunchIn) {
            return null;
        }

        $lunchOutTime = Carbon::createFromTimeString($lunchOut);
        $lunchInTime = Carbon::createFromTimeString($lunchIn);

        $morningMinutes = $logInTime->diffInMinutes($lunchOutTime, false);
        $afternoonMinutes = $lunchInTime->diffInMinutes($logOutTime, false);

        if ($morningMinutes <= 0 || $afternoonMinutes <= 0) {
            return null;
        }

        $totalMinutes = $morningMinutes + $afternoonMinutes - 30;
        if ($totalMinutes < 0) {
            $totalMinutes = 0;
        }

        return sprintf('%02d:%02d:00', intdiv($totalMinutes, 60), $totalMinutes % 60);
    }
}
