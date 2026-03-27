<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Employee::with([
            'company:id,name',
            'department:id,name',
            'designation:id,name',
            'personalDetail:id,emp_id,img_path,mobile'
        ])
            ->withCount(['nominees', 'family']);

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
            $query->where('department_id', $request->department_id);
        }

        // 🏷 Designation filter
        if ($request->designation_id) {
            $query->where('designation_id', $request->designation_id);
        }

        // ✅ Status filter
        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $employees = $query
            ->latest()
            ->paginate(5)
            ->withQueryString(); // 🔥 important

        return inertia('Employee', [
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

            'categories' => Category::where('is_active', true)
                ->get(['id', 'name', 'company_id']),

            'designations' => Designation::where('is_active', true)
                ->get(['id', 'name', 'company_id']),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Employee
            'employee.actual_emp_id' => 'required|unique:employees,actual_emp_id',
            'employee.code' => 'required|unique:employees,code',
            'employee.name' => 'required|string',
            'employee.gender' => 'required',
            'employee.d_o_j' => 'required|date',
            'employee.company_id' => 'required|exists:companies,id',
            'employee.dept_id' => 'required|exists:departments,id',
            'employee.cat_id' => 'required|exists:categories,id',
            'employee.des_id' => 'required|exists:designations,id',

            // Personal

            'personal.img' => 'nullable|image|max:2048',
            'personal.mobile' => 'nullable|string',
            'personal.parent_name' => 'nullable|string',
            'personal.marital_status' => 'nullable|string',
            'personal.d_o_b' => 'nullable|date',
            'personal.age' => 'nullable|numeric',
            'personal.present_address' => 'nullable|string',
            'personal.permanent_address' => 'nullable|string',
            'personal.religion' => 'nullable|string',
            'personal.physically_challenged' => 'nullable|boolean',
            'personal.if_yes_details' => 'nullable|string',


            // Nominees
            'nominees' => 'array',
            'nominees.*.name' => 'required|string',
            'nominees.*.relationship' => 'required|string',
            'nominees.*.d_o_b' => 'nullable|date',
            'nominees.*.age' => 'nullable|numeric',
            'nominees.*.profession' => 'nullable|string',
            'nominees.*.salary' => 'nullable|numeric',
            'nominees.*.address' => 'nullable|string',


            // Family
            'family' => 'array',
            'family.*.name' => 'nullable|string',
            'family.*.d_o_b' => 'nullable|date',
            'family.*.age' => 'nullable|numeric',
            'family.*.profession' => 'nullable|string',
            'family.*.earnings' => 'nullable|numeric',
            'family.*.relationship' => 'nullable|string',

        ]);

        DB::transaction(function () use ($request, $validated) {

            $employee = Employee::create($validated['employee']);

            // PERSONAL
            if (!empty($validated['personal'])) {
                $personal = $validated['personal'];

                if ($request->hasFile('personal.img')) {
                    $personal['img_path'] = $request->file('personal.img')
                        ->store('employees', 'public');
                }

                $employee->personalDetail()->create($personal);
            }

            // NOMINEES
            if (!empty($validated['nominees'])) {
                $employee->nominees()->createMany($validated['nominees']);
            }

            // FAMILY
            if (!empty($validated['family'])) {
                $employee->family()->createMany($validated['family']);
            }
        });

        return redirect()->route('employees.index')
            ->with('toast', ['type' => 'success', 'message' => 'Employee created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $employee->load([
            'company',
            'department',
            'designation',
            'personalDetail',
            'family',
            'nominees'
        ]);

        return response()->json($employee);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            // Employee
            'employee.actual_emp_id' => 'required|unique:employees,actual_emp_id,' . $employee->id,
            'employee.code' => 'required|unique:employees,code,' . $employee->id,
            'employee.name' => 'required|string',
            'employee.gender' => 'required',
            'employee.d_o_j' => 'required|date',
            'employee.company_id' => 'required|exists:companies,id',
            'employee.dept_id' => 'required|exists:departments,id',
            'employee.cat_id' => 'required|exists:categories,id',
            'employee.des_id' => 'required|exists:designations,id',

            // Personal
            'personal.img' => 'nullable|image|max:2048',
            'personal.mobile' => 'nullable|string',
            'personal.parent_name' => 'nullable|string',
            'personal.marital_status' => 'nullable|string',
            'personal.d_o_b' => 'nullable|date',
            'personal.age' => 'nullable|numeric',
            'personal.present_address' => 'nullable|string',
            'personal.permanent_address' => 'nullable|string',
            'personal.religion' => 'nullable|string',
            'personal.physically_challenged' => 'nullable|boolean',
            'personal.if_yes_details' => 'nullable|string',

            // Nominees
            'nominees' => 'array',
            'nominees.*.name' => 'required|string',
            'nominees.*.relationship' => 'required|string',
            'nominees.*.d_o_b' => 'nullable|date',
            'nominees.*.age' => 'nullable|numeric',
            'nominees.*.profession' => 'nullable|string',
            'nominees.*.salary' => 'nullable|numeric',
            'nominees.*.address' => 'nullable|string',

            // Family
            'family' => 'array',
            'family.*.name' => 'nullable|string',
            'family.*.d_o_b' => 'nullable|date',
            'family.*.age' => 'nullable|numeric',
            'family.*.profession' => 'nullable|string',
            'family.*.earnings' => 'nullable|numeric',
            'family.*.relationship' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $employee, $validated) {

            // UPDATE EMPLOYEE
            $employee->update($validated['employee']);

            // PERSONAL (update or create)
            if (!empty($validated['personal'])) {
                $personal = $validated['personal'];

                if ($request->hasFile('personal.img')) {

                    // delete old image
                    if ($employee->personalDetail?->img_path) {
                        Storage::disk('public')
                            ->delete($employee->personalDetail->img_path);
                    }

                    $personal['img_path'] = $request->file('personal.img')
                        ->store('employees', 'public');
                }

                $employee->personalDetail()->updateOrCreate(
                    ['emp_id' => $employee->id],
                    $personal
                );
            }

            // NOMINEES → replace
            $employee->nominees()->delete();
            if (!empty($validated['nominees'])) {
                $employee->nominees()->createMany($validated['nominees']);
            }

            // FAMILY → replace
            $employee->family()->delete();
            if (!empty($validated['family'])) {
                $employee->family()->createMany($validated['family']);
            }
        });

        return redirect()->route('employees.index')
            ->with('toast', ['type' => 'success', 'message' => 'Employee updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        DB::transaction(function () use ($employee) {

            // delete image if exists
            if ($employee->personalDetail?->img_path) {
                Storage::disk('public')
                    ->delete($employee->personalDetail->img_path);
            }

            $employee->delete(); // cascade handles children
        });

        return redirect()->route('employees.index')
            ->with('toast', ['type' => 'success', 'message' => 'Employee deleted successfully']);
    }
}
