<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\Employee;
use App\Models\Roster;
use App\Models\ShiftMaster;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RosterController extends Controller
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

        $shifts = ShiftMaster::select('id', 'code', 'description')
            ->whereIn('company_id', $userCompanyIds)
            ->orderBy('code')
            ->get();

        $rosters = Roster::with(['employee', 'shift'])
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

        $summary = $employees->map(function ($employee) use ($rosters) {
            $employeeRosters = $rosters->where('employee_id', $employee->id);
            $shiftCodes = $employeeRosters->map(function ($roster) {
                return optional($roster->shift)->code;
            })->filter()->unique()->values()->all();

            return [
                'id' => $employee->id,
                'name' => $employee->name,
                'designation_id' => $employee->des_id,
                'designation' => optional($employee->designation)->name,
                'assigned_days' => $employeeRosters->count(),
                'shift_codes' => $shiftCodes,
            ];
        });

        return inertia('Roster', [
            'rosters' => $rosters,
            'employees' => $employees,
            'shifts' => $shifts,
            'summary' => $summary,
            'designations' => $designations,
            'selected_designation' => $selectedDesignation,
            'month_days' => $monthDays,
            'selected_month' => $month,
            'selected_year' => $year,
        ]);
    }

    /**
     * Store a single roster entry or bulk update roster for an employee-month.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // 'employee_id' => 'required_without:employee_ids.*|exists:employees,id',
            'date' => 'nullable|date',
            'shift_id' => 'nullable|exists:shift_master,id',
            'month' => 'required_with:assignments|integer|min:1|max:12',
            'year' => 'required_with:assignments|integer|min:2000|max:2100',
            'assignments' => 'required|array',
            'assignments.*.day' => 'required_with:assignments|integer|min:1|max:31',
            'assignments.*.shift_id' => 'nullable|exists:shift_master,id',
            'employee_ids' => 'nullable|array',
            'employee_ids.*' => 'integer|exists:employees,id',
        ]);

        $employeeIds = $validated['employee_ids'] ?? [$validated['employee_id']];
        $month = $validated['month'];
        $year = $validated['year'];
        $assignments = $validated['assignments'];

        try {
            DB::beginTransaction();
            foreach ($employeeIds as $employeeId) {
                Roster::where('employee_id', $employeeId)
                    ->whereMonth('date', $month)
                    ->whereYear('date', $year)
                    ->delete();
            }

            foreach ($employeeIds as $employeeId) {
                foreach ($assignments as $assignment) {
                    if (empty($assignment['shift_id'])) {
                        continue;
                    }

                    $date = Carbon::create($year, $month, $assignment['day'])->toDateString();

                    Roster::create([
                        'employee_id' => $employeeId,
                        'date' => $date,
                        'shift_id' => $assignment['shift_id'],
                    ]);
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('toast', ['type' => 'error', 'message' => 'An error occurred while updating the roster. Please try again.']);
        }

        return back()->with('toast', ['type' => 'success', 'message' => 'Roster updated successfully.']);
    }

    /**
     * Delete an employee's roster for the selected month.
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'integer|exists:employees,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
        ]);

        $employeeIds = $validated['employee_ids'];

        foreach ($employeeIds as $employeeId) {
            Roster::where('employee_id', $employeeId)
                ->whereMonth('date', $validated['month'])
                ->whereYear('date', $validated['year'])
                ->delete();
        }

        return back()->with('toast', ['type' => 'success', 'message' => 'Roster month deleted successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Roster $roster)
    {
        //
    }

    /**
     * Update a single roster record.
     * Kept for single-entry API usage outside the month grid.
     */
    public function update(Request $request, Roster $roster)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'shift_id' => 'required|exists:shift_master,id',
        ]);

        $roster->update($validated);

        return back()->with('toast', ['type' => 'success', 'message' => 'Roster updated successfully.']);
    }

    /**
     * Remove a single roster entry.
     */
    public function destroy(Roster $roster)
    {
        $roster->delete();

        return back()->with('toast', ['type' => 'success', 'message' => 'Roster deleted successfully.']);
    }
}
