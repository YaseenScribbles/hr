<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::all();
        return inertia('Company', compact('companies'));
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
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'gst' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
        ]);

        $company = Company::create([
            ...$request->only([
                'name',
                'address',
                'district',
                'state',
                'pincode',
                'email',
                'phone',
                'gst',
                'website'
            ]),
            'created_by' => $request->user()->id,
        ]);

        CompanyUser::create([
            'user_id' => $request->user()->id,
            'company_id' => $company->id
        ]);

        return redirect()->route('companies.index')->with('toast', ['type' => 'success', 'message' => 'Company created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'gst' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'is_active' => 'required|boolean',
        ]);

        $company->update([
            'name' => $request->name,
            'address' => $request->address,
            'district' => $request->district,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'email' => $request->email,
            'phone' => $request->phone,
            'gst' => $request->gst,
            'website' => $request->website,
            'is_active' => $request->is_active,
            'updated_by' => $request->user()->id
        ]);

        return redirect()->route('companies.index')->with('toast', ['type' => 'success', 'message' => 'Company updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('toast', ['type' => 'success', 'message' => 'Company deleted successfully']);
    }
}
