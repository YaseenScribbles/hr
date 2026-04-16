<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userCompanyIds = Auth::user()->companies->pluck('id')->toArray();
        $categories = Category::with('company')->whereIn('company_id', $userCompanyIds)->get();
        return inertia('Category', compact('categories'));
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
            'name' => 'required|string|max:255',
            'company_id' => ['required', 'integer', Rule::in($userCompanyIds)]
        ]);

        Category::create([...$request->only('name', 'company_id'), 'created_by' => $request->user()->id]);

        return redirect()->route('categories.index')->with('toast', ['type' => 'success', 'message' => 'Category created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $userCompanyIds = Auth::user()->companies->pluck('id')->toArray();
        $request->validate([
            'name' => 'required|string|max:255',
            'active' => 'required|boolean',
            'company_id' => ['required', 'integer', Rule::in($userCompanyIds)]
        ]);

        $category->update([
            'name' => $request->name,
            'is_active' => $request->active,
            'company_id' => $request->company_id,
            'created_by' => $request->user()->id
        ]);

        return redirect()->route('categories.index')->with('toast', ['type' => 'success', 'message' => 'Category updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('toast', ['type' => 'success', 'message' => 'Category deleted successfully']);
    }
}
