<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\Department;
use App\Models\Designation;
use App\Models\AttdSalary;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use NumberFormatter;

class PdfController extends Controller
{

    public function Attendence()
    {
        $emp_array = DB::select("SELECT d.name [designation], e.code, e.name, epd.parent_name, ef.profession, epd.d_o_b, epd.age,
        e.gender, epd.present_address, epd.permanent_address, epd.mobile, epd.marital_status, e.d_o_j, e.salary, dep.name[department],
        c.name[category]
        FROM employees e
        JOIN designations d ON e.des_id = d.id
        JOIN departments dep ON e.dept_id = dep.id
        JOIN categories c ON e.cat_id = c.id
        JOIN emp_family ef ON ef.emp_id = e.id
        JOIN emp_personal_details epd ON epd.emp_id = e.id
        WHERE e.id = 9");

        $emp = $emp_array[0];
        return view('employee.attendance', compact('emp'));
    }

    public function generateBioData(Employee $employee)
    {
        $emp_array = DB::select("SELECT d.name [designation], e.code, e.name, epd.parent_name, epd.d_o_b, epd.age,
        e.gender, epd.present_address, epd.permanent_address, epd.mobile, epd.marital_status,
        e.esi_number, e.pf_number, epd.img_path
        FROM employees e
        JOIN designations d ON e.des_id = d.id
        JOIN emp_family ef ON ef.emp_id = e.id
        JOIN emp_personal_details epd ON epd.emp_id = e.id
        WHERE e.id = ?", [$employee->id]);

        $emp = $emp_array[0];

        $emp_family = DB::select("SELECT name, d_o_b, age, relationship, profession
        FROM emp_family
        WHERE emp_id = ?", [$employee->id]);

        $company = $employee->company()->first();

        return view('employee.bio-data', compact('emp', 'emp_family', 'company'));
    }

    public function generateTermsAndConditions(Employee $employee)
    {
        $emp_array = DB::select("SELECT d.name [designation], e.code, e.name,e.d_o_j,
        e.salary, dep.name[department]
        FROM employees e
        JOIN designations d ON e.des_id = d.id
        JOIN departments dep ON e.dept_id = dep.id
        WHERE e.id = ?", [$employee->id]);

        $emp = $emp_array[0];

        $company = $employee->company()->first();

        return view('employee.t-and-c', compact('emp', 'company'));
    }

    public function generateAppointmentOrder(Employee $employee)
    {
        $emp_array = DB::select("SELECT d.name [designation], e.code, e.name, epd.present_address, e.d_o_j, e.salary, dep.name[department]
        FROM employees e
        JOIN designations d ON e.des_id = d.id
        JOIN departments dep ON e.dept_id = dep.id
        JOIN emp_personal_details epd ON epd.emp_id = e.id
        WHERE e.id = ?", [$employee->id]);

        $emp = $emp_array[0];

        $company = $employee->company()->first();

        return view('employee.appointmentOrder', compact('emp', 'company'));
    }

    public function generateInductionTraining(Employee $employee)
    {
        $emp_array = DB::select("SELECT e.name, e.d_o_j, e.code
        FROM employees e
        WHERE e.id = ?", [$employee->id]);

        $emp = $emp_array[0];

        return view('employee.induction', compact('emp'));
    }

    public function generateFormV(Employee $employee)
    {
        $emp_array = DB::select("SELECT e.name, e.code, e.d_o_j, e.salary
        FROM employees e
        WHERE e.id = ?", [$employee->id]);

        $emp = $emp_array[0];

        $company = $employee->company()->first();

        return view('employee.form', compact('emp', 'company'));
    }

    public function generateFormF(Employee $employee)
    {
        $emp_array = DB::select("SELECT e.code, e.name, e.d_o_j, e.gender, epd.religion, epd.permanent_address, dep.name AS department, epd.marital_status
        FROM employees e
        JOIN emp_personal_details epd ON epd.emp_id = e.id
        JOIN departments dep ON e.dept_id = dep.id
        WHERE e.id = ?", [$employee->id]);

        $emp = $emp_array[0];

        $emp_nominees = DB::select("SELECT name, d_o_b, age, relationship, profession, address
        FROM emp_nominees
        WHERE emp_id = ?", [$employee->id]);

        $company = $employee->company()->first();

        return view('employee.form-F', compact('emp', 'company', 'emp_nominees'));
    }

    public function generateForm2(Employee $employee)
    {
        $emp_array = DB::select("SELECT e.name, e.code, epd.parent_name, epd.d_o_b, e.gender, epd.marital_status, epd.permanent_address
        FROM employees e
        JOIN emp_personal_details epd ON epd.emp_id = e.id
        WHERE e.id = ?", [$employee->id]);

        $emp = $emp_array[0];

        $emp_nominees = DB::select("SELECT name, d_o_b, age, relationship, profession, address
        FROM emp_nominees
        WHERE emp_id = ?", [$employee->id]);

        return view('employee.form-2', compact('emp', 'emp_nominees'));
    }

    public function generateESIC(Employee $employee)
    {
        $emp_array = DB::select("SELECT e.code, e.name, epd.d_o_b, e.d_o_j, epd.parent_name, e.gender, epd.present_address, epd.permanent_address, dep.name AS department,
        d.name AS designation, epd.marital_status
        FROM employees e
        JOIN emp_personal_details epd ON epd.emp_id = e.id
        JOIN departments dep ON e.dept_id = dep.id
        JOIN designations d ON e.des_id = d.id
        WHERE e.id = ?", [$employee->id]);

        $emp = $emp_array[0];

        $emp_family = DB::select("SELECT name, d_o_b, age, relationship, profession, residing_with
        FROM emp_family
        WHERE emp_id = ?", [$employee->id]);

        $emp_nominees = DB::select("SELECT name, d_o_b, age, relationship, profession, address, residing_with
        FROM emp_nominees
        WHERE emp_id = ?", [$employee->id]);

        $company = $employee->company()->first();

        return view('employee.esic', compact('emp', 'emp_family', 'company', 'emp_nominees'));
    }

    public function generateForm34(Employee $employee)
    {
        $emp_array = DB::select("SELECT e.code,e.name, e.d_o_j, d.name AS designation
        FROM employees e
        JOIN designations d ON e.des_id = d.id
        WHERE e.id = ?", [$employee->id]);

        $emp = $emp_array[0];

        $emp_nominees = DB::select("SELECT name, d_o_b, age, relationship, profession, address, residing_with
        FROM emp_nominees
        WHERE emp_id = ?", [$employee->id]);

        $company = $employee->company()->first();

        return view('employee.form_No-34', compact('emp', 'company', 'emp_nominees'));
    }

    public function employeePdfDocuments(Employee $employee)
    {
        $company = $employee->company()->first();

        return view('employee.pdf-documents', compact('employee', 'company'));
    }

    public function generateForm25B(Request $request, Employee $employee)
    {

        $month = $request->query('month') ?? Carbon::now()->month;
        $year = $request->query('year') ?? Carbon::now()->year;

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $endDate = $startDate->copy()->endOfMonth()->endOfDay();

        $salaryExists = AttdSalary::where('employee_id', $employee->id)
            ->whereDate('from_date', '>=', $startDate)
            ->whereDate('to_date', '<=', $endDate)
            ->exists();

        if (! $salaryExists) {
            if ($request->query('check')) {
                return response()->json(['message' => 'Cannot generate Form 25B. No salary data found for the selected month.'], 422);
            }

            return back()->with('toast', ['type' => 'error', 'message' => 'Cannot generate Form 25B. No salary data found for the selected month.']);
        }

        $emp_array = DB::select("SELECT e.code, e.name,e.d_o_j, epd.d_o_b,
        e.esi_number, e.pf_number, dep.name AS department, d.name AS designation
        FROM employees e
        JOIN departments dep ON e.dept_id = dep.id
        JOIN designations d ON e.des_id = d.id
        JOIN emp_personal_details epd ON epd.emp_id = e.id
        WHERE e.id = ?", [$employee->id]);

        $emp = $emp_array[0];

        $company = $employee->company()->first();

        $salary = DB::select("SELECT from_date, to_date, worked_shift, wages, gross_salary, esi, pf, advance, net_salary, created_at
         FROM attd_salary
         WHERE employee_id = ? AND from_date >= ? AND to_date <= ?", [
            $employee->id,
            $startDate->toDateString(),
            $endDate->toDateString(),
        ]);

        $formatter = new NumberFormatter('en_IN', NumberFormatter::SPELLOUT);
        $amountInWords = ucwords($formatter->format($salary[0]->net_salary ?? 0)) . ' RUPEES ONLY';

        return view('employee.form-25B', compact('emp', 'company', 'salary', 'amountInWords', 'startDate', 'endDate'));
    }

    public function generateTimingReport(Request $request, Employee $employee)
    {
        $month = $request->query('month') ?? Carbon::now()->month;
        $year = $request->query('year') ?? Carbon::now()->year;

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $endDate = $startDate->copy()->endOfMonth()->endOfDay();

        $attendanceExists = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->exists();

        if (! $attendanceExists) {
            if ($request->query('check')) {
                return response()->json(['message' => 'Cannot generate timing report. No attendance data found for the selected month.'], 422);
            }

            return back()->with('toast', ['type' => 'error', 'message' => 'Cannot generate timing report. No attendance data found for the selected month.']);
        }

        $emp_array = DB::select("SELECT e.code, e.name,e.d_o_j,
        dep.name AS department, d.name AS designation, e.gender
        FROM employees e
        JOIN departments dep ON e.dept_id = dep.id
        JOIN designations d ON e.des_id = d.id
        WHERE e.id = ?", [$employee->id]);

        $emp = $emp_array[0];

        $timings = collect(DB::select("SELECT a.date, s.code AS shift_code, a.log_in, a.lunch_out, a.lunch_in, a.log_out, a.actual_hours, a.ot_in,
        a.ot_out,a.total_hours, a.status
        FROM attendance a
        JOIN shift_master s ON a.shift_id = s.id
        WHERE employee_id = ? AND date >= ? AND date <= ?", [
            $employee->id,
            $startDate->toDateString(),
            $endDate->toDateString(),
        ]));

        $company = $employee->company()->first();

        $daysInMonth = $timings ? count($timings) : 0;
        $weeklyOff = $timings->filter(function ($timing) {
            return $timing->status == "WH";
        })->count();
        $presentDays = $timings->filter(function ($timing) {
            return $timing->status == "X";
        })->count();
        $absentDays = $timings->filter(function ($timing) {
            return $timing->status == "A";
        })->count();
        $halfDays = $timings->filter(function ($timing) {
            return ($timing->status == "/A" || $timing->status == "A/");
        })->count();

        return view('employee.timing-report', compact('emp', 'company', 'timings', 'startDate', 'endDate', 'daysInMonth', 'weeklyOff', 'presentDays', 'absentDays', 'halfDays'));
    }

    public function generateAttendanceReport(Request $request, Company $company)
    {
        $month = $request->query('month') ?? Carbon::now()->month;
        $year = $request->query('year') ?? Carbon::now()->year;

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $endDate = $startDate->copy()->endOfMonth()->endOfDay();

        $attendanceExists = Attendance::whereHas('employee', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->exists();

        $salaryExists = AttdSalary::whereHas('employee', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })
            ->whereDate('from_date', '>=', $startDate)
            ->whereDate('to_date', '<=', $endDate)
            ->exists();

        if (! $attendanceExists || ! $salaryExists) {
            if ($request->query('check')) {
                return response()->json(['message' => 'Cannot generate attendance report. Missing attendance or salary data for the selected month.'], 422);
            }

            return back()->with('toast', ['type' => 'error', 'message' => 'Cannot generate attendance report. Missing attendance or salary data for the selected month.']);
        }

        $attendanceRows = collect(DB::select("SELECT c.id as category_id, c.name as category, d.id as department_id, d.name as department,
            de.id as designation_id, de.name as designation, e.id as employee_id, e.code, e.name, a.date, a.status,
            s.wages, s.worked_shift, s.gross_salary, s.esi, s.pf, s.advance, s.net_salary,
            0 as present_days, 0 as casual_leave,
            0 as ot_hours, 0 as ot_wages
            FROM attendance a
            JOIN employees e ON a.employee_id = e.id
            JOIN categories c ON e.cat_id = c.id
            JOIN departments d ON e.dept_id = d.id
            JOIN designations de ON e.des_id = de.id
            JOIN attd_salary s ON e.id = s.employee_id
            WHERE e.company_id = ? AND a.date >= ? AND a.date <= ?",
            [$company->id, $startDate->toDateString(), $endDate->toDateString()]));

        $attendanceData = $attendanceRows
            ->groupBy('employee_id')
            ->map(function ($rows) use ($startDate, $endDate) {
                $row = $rows->first();
                $dailyStatuses = [];

                foreach ($rows as $attendance) {
                    $day = Carbon::parse($attendance->date)->day;
                    $dailyStatuses[$day] = $attendance->status;
                }

                return (object) [
                    'employee_id' => $row->employee_id,
                    'category_id' => $row->category_id,
                    'category' => $row->category,
                    'department_id' => $row->department_id,
                    'department' => $row->department,
                    'designation_id' => $row->designation_id,
                    'designation' => $row->designation,
                    'code' => $row->code,
                    'name' => $row->name,
                    'daily_statuses' => $dailyStatuses,
                    'wages' => $row->wages,
                    'worked_shift' => $row->worked_shift,
                    'present_days' => $row->present_days,
                    'casual_leave' => $row->casual_leave,
                    'gross_salary' => $row->gross_salary,
                    'ot_hours' => $row->ot_hours,
                    'ot_wages' => $row->ot_wages,
                    'esi' => $row->esi,
                    'pf' => $row->pf,
                    'advance' => $row->advance,
                    'net_salary' => $row->net_salary,
                ];
            })
            ->values();

        $categoryIds = $attendanceData->pluck('category_id')->unique()->values()->all();
        $departmentIds = $attendanceData->pluck('department_id')->unique()->values()->all();
        $designationIds = $attendanceData->pluck('designation_id')->unique()->values()->all();

        $categories = Category::where('company_id', $company->id)
            ->whereIn('id', $categoryIds)
            ->get();
        $departments = Department::where('company_id', $company->id)
            ->whereIn('id', $departmentIds)
            ->get();
        $designations = Designation::where('company_id', $company->id)
            ->whereIn('id', $designationIds)
            ->get();

        return view('employee.attendance', compact('categories', 'departments', 'designations', 'attendanceData', 'company', 'startDate', 'endDate'));
    }
}
