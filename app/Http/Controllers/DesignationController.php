<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userCompanyIds = Auth::user()->companies->pluck('id')->toArray();
        $designations = Designation::with('company')->whereIn('company_id', $userCompanyIds)->get();
        return inertia('Designation', compact('designations'));
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

        Designation::create([...$request->only('name', 'company_id'), 'created_by' => $request->user()->id]);
        return redirect()->route('designations.index')->with('toast', ['type' => 'success', 'message' => 'Designation created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Designation $designation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Designation $designation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Designation $designation)
    {
        $userCompanyIds = Auth::user()->companies->pluck('id')->toArray();
        $request->validate([
            'name' => 'required|string|max:255',
            'active' => 'required|boolean',
            'company_id' => ['required', 'integer', Rule::in($userCompanyIds)]
        ]);

        $designation->update([
            'name' => $request->name,
            'is_active' => $request->active,
            'company_id' => $request->company_id,
            'updated_by' => $request->user()->id,
        ]);
        return redirect()->route('designations.index')->with('toast', ['type' => 'success', 'message' => 'Designation updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Designation $designation)
    {
        $designation->delete();
        return redirect()->route('designations.index')->with('toast', ['type' => 'success', 'message' => 'Designation deleted successfully.']);
    }
}
