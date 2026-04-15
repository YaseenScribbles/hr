<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttdSalary;
use App\Models\Deduction;
use App\Models\Defaults;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //share dedution for the particualar company user is associated with with user_company table,
        // user might associated with multiple company, so we need to get the company id from the user_company table and then get the deductions for that company

        $companies = Auth::user()->companies;

        //share employees for the particualar company user is associated with with user_company table,
        // user might associated with multiple company, so we need to get the company id from the user_company table and then get the employees for that company

        $employees = Employee::whereIn('company_id', $companies->pluck('id'))->get();

        $deductions = Deduction::whereIn('employee_id', function ($query) use ($companies) {
            $query->select('id')
                ->from('employees')
                ->whereIn('company_id', $companies->pluck('id'));
        })->with('employee')->get();

        return inertia('Deductions', [
            'deductions' => $deductions,
            'employees' => $employees,
            'companies' => $companies,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'type' => 'required|string',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'amount' => 'required|numeric|min:0',
        ]);

        Deduction::create($validated);

        return redirect()->route('deductions.index')->with('toast', ['type' => 'success', 'message' => 'Deduction created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function generateSalary(Request $request)
    {
        $companies = Auth::user()->companies;

        $validated = $request->validate([
            'company_id' => ['required', 'integer', 'in:'.$companies->pluck('id')->implode(',')],
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
        ]);

        $fromDate = \Carbon\Carbon::createFromDate($validated['year'], $validated['month'], 1)->startOfDay();
        $toDate = $fromDate->copy()->endOfMonth()->endOfDay();

        $employees = Employee::where('company_id', $validated['company_id'])
            ->where('status', 1)
            ->get();

        $esiRate = (float) Defaults::where('key', 'ESI')->value('value') ?: 0;
        $pfRate = (float) Defaults::where('key', 'PF')->value('value') ?: 0;

        $employeeIdsWithAttendance = Attendance::whereIn('employee_id', $employees->pluck('id'))
            ->whereBetween('date', [$fromDate->toDateString(), $toDate->toDateString()])
            ->distinct()
            ->pluck('employee_id');

        $missingAttendanceEmployees = $employees->whereNotIn('id', $employeeIdsWithAttendance);

        if ($missingAttendanceEmployees->isNotEmpty()) {
            $names = $missingAttendanceEmployees->pluck('name')->implode(', ');

            return redirect()->route('deductions.index')->with('toast', [
                'type' => 'error',
                'message' => 'Salary generation failed. Attendance missing for active employee(s): '.$names,
            ]);
        }

        foreach ($employees as $employee) {
            $attendances = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$fromDate->toDateString(), $toDate->toDateString()])
                ->get();

            $workedDays = $attendances->whereIn('status', ['X', '/A', 'A/'])->count();
            $holidayDays = $attendances->where('status', 'WH')->count();
            $absentDays = $attendances->where('status', 'A')->count();
            $workedShift = $attendances->sum(function ($attendance) {
                if ($attendance->status === 'X') {
                    return 1;
                }

                if (in_array($attendance->status, ['/A', 'A/'])) {
                    return 0.5;
                }

                return 0;
            });

            $wages = (float) $employee->salary;
            $grossSalary = round($workedShift * $wages);
            $esi = $employee->esi_eligible
                ? round($grossSalary * $esiRate / 100)
                : 0;
            $pf = $grossSalary >= 15000
                ? 1800
                : round($grossSalary * $pfRate / 100);

            $advance = Deduction::where('employee_id', $employee->id)
                ->where('type', 'advance')
                ->whereDate('from_date', '>=', $fromDate->toDateString())
                ->whereDate('to_date', '<=', $toDate->toDateString())
                ->sum('amount');

            $netSalary = round($grossSalary - $esi - $pf - $advance);

            AttdSalary::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'from_date' => $fromDate->toDateString(),
                    'to_date' => $toDate->toDateString(),
                ],
                [
                    'worked_days' => $workedDays,
                    'worked_shift' => $workedShift,
                    'holiday_days' => $holidayDays,
                    'absent_days' => $absentDays,
                    'wages' => $wages,
                    'gross_salary' => $grossSalary,
                    'esi' => $esi,
                    'pf' => $pf,
                    'advance' => $advance,
                    'net_salary' => $netSalary,
                ]
            );
        }

        return redirect()->route('deductions.index')->with('toast', [
            'type' => 'success',
            'message' => 'Salary generated for '.$employees->count().' active employees.',
        ]);
    }

    public function show(Deduction $deduction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Deduction $deduction)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'type' => 'required|string',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'amount' => 'required|numeric|min:0',
        ]);

        $deduction->update($validated);

        return redirect()->route('deductions.index')->with('toast', ['type' => 'success', 'message' => 'Deduction updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deduction $deduction)
    {
        $deduction->delete();

        return redirect()->route('deductions.index')->with('toast', ['type' => 'success', 'message' => 'Deduction deleted successfully']);
    }
}
