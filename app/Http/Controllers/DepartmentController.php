<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Load departments and companies only for companies the user is associated with
        $departmentCompanyIds = Auth::user()->companies->pluck('id')->toArray();

        $departments = Department::with('company')
            ->whereIn('company_id', $departmentCompanyIds)
            ->get();

        return inertia('Department', [
            'departments' => $departments,
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
        $userCompanyIds = Auth::user()->companies->pluck('id')->toArray();

        $request->validate([
            'company_id' => ['required', 'integer', Rule::in($userCompanyIds)],
            'name' => 'required|string|max:255',
        ]);

        Department::create([...$request->only('name', 'company_id'), 'created_by' => $request->user()->id]);
        return redirect()->route('departments.index')->with('toast', [ 'type' => 'success', 'message' => 'Department created successfully.' ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $userCompanyIds = Auth::user()->companies->pluck('id')->toArray();

        $request->validate([
            'name' => 'required|string|max:255',
            'active' => 'required|boolean',
            'company_id' => ['required', 'integer', Rule::in($userCompanyIds)],
        ]);

        $department->update([
            'name' => $request->name,
            'is_active' => $request->active,
            'company_id' => $request->company_id,
            'updated_by' => $request->user()->id,
        ]);
        return redirect()->route('departments.index')->with('toast', [ 'type' => 'success', 'message' => 'Department updated successfully.' ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index')->with('toast', [ 'type' => 'success', 'message' => 'Department deleted successfully.' ]);
    }
}
