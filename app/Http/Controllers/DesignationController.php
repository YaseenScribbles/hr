<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $designations = Designation::with('company')->get();
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
        $request->validate([
            'company_id' => 'required|exists:companies,id',
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
        $request->validate([
            'name' => 'required|string|max:255',
            'active' => 'required|boolean',
            'company_id' => 'required|exists:companies,id'
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
