<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {

        $query = Employee::with('company', 'department', 'designation');

        // 🔍 Search (name + mobile)
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhereHas('personalDetail', function ($q2) use ($request) {
                        $q2->where('mobile', 'like', "%{$request->search}%");
                    });
            });
        }

        // 🏢 Company filter
        if ($request->company_id) {
            $query->where('company_id', $request->company_id);
        }

        // 🧑‍💼 Department filter
        if ($request->department_id) {
            $query->where('dept_id', $request->department_id);
        }

        // 🏷 Designation filter
        if ($request->designation_id) {
            $query->where('des_id', $request->designation_id);
        }

        // ✅ Status filter
        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $employees = $query
            ->latest()
            ->paginate(5)
            ->withQueryString(); // 🔥 important

        return inertia('Reports', [
            'employees' => $employees,

            // 👇 send filters back to frontend
            'filters' => $request->only([
                'search',
                'company_id',
                'department_id',
                'designation_id',
                'status'
            ]),

            'departments' => Department::where('is_active', true)
                ->get(['id', 'name', 'company_id']),

            'designations' => Designation::where('is_active', true)
                ->get(['id', 'name', 'company_id']),
        ]);
    }
}
