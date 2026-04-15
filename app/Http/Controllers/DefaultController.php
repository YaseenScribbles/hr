<?php

namespace App\Http\Controllers;

use App\Models\Defaults;
use Illuminate\Http\Request;

class DefaultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $defaults = Defaults::all();

        return inertia('Defaults', [
            'defaults' => $defaults,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:defaults,key',
            'value' => 'nullable|string',
        ]);

        Defaults::create($validated);

        return redirect()->route('defaults.index')->with('toast', ['type' => 'success', 'message' => 'Default created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Defaults $defaults)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Defaults $default)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:defaults,key,' . $default->id,
            'value' => 'nullable|string',
        ]);

        $default->update($validated);

        return redirect()->route('defaults.index')->with('toast', ['type' => 'success', 'message' => 'Default updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Defaults $default)
    {
        $default->delete();

        return redirect()->route('defaults.index')->with('toast', ['type' => 'success', 'message' => 'Default deleted successfully']);
    }
}
