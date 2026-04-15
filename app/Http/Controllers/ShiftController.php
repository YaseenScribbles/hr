<?php

namespace App\Http\Controllers;

use App\Models\ShiftMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('ShiftMaster', [
            'shifts' => ShiftMaster::with('company')
                ->latest()
                ->get(),
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
            'company_id' => 'required|exists:companies,id',
            'code' => 'required|string|unique:shift_master,code',
            'description' => 'nullable|string',

            // times (nullable)
            'login' => 'nullable|date_format:H:i',
            'login_min' => 'nullable|date_format:H:i',
            'login_max' => 'nullable|date_format:H:i',

            'logout' => 'nullable|date_format:H:i',
            'logout_min' => 'nullable|date_format:H:i',
            'logout_max' => 'nullable|date_format:H:i',

            'lunch_in' => 'nullable|date_format:H:i',
            'lunch_in_min' => 'nullable|date_format:H:i',
            'lunch_in_max' => 'nullable|date_format:H:i',

            'lunch_out' => 'nullable|date_format:H:i',
            'lunch_out_min' => 'nullable|date_format:H:i',
            'lunch_out_max' => 'nullable|date_format:H:i',

            'ot_in' => 'nullable|date_format:H:i',
            'ot_in_min' => 'nullable|date_format:H:i',
            'ot_in_max' => 'nullable|date_format:H:i',

            'ot_out' => 'nullable|date_format:H:i',
            'ot_out_min' => 'nullable|date_format:H:i',
            'ot_out_max' => 'nullable|date_format:H:i',
        ]);

        ShiftMaster::create($validated);

        return redirect()
            ->route('shifts.index')
            ->with('toast', ['type' => 'success', 'message' => 'Shift created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShiftMaster $shiftMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShiftMaster $shiftMaster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShiftMaster $shift)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'code' => 'required|string|unique:shift_master,code,' . $shift->id,
            'description' => 'nullable|string',

            'login' => 'nullable|date_format:H:i',
            'login_min' => 'nullable|date_format:H:i',
            'login_max' => 'nullable|date_format:H:i',

            'logout' => 'nullable|date_format:H:i',
            'logout_min' => 'nullable|date_format:H:i',
            'logout_max' => 'nullable|date_format:H:i',

            'lunch_in' => 'nullable|date_format:H:i',
            'lunch_in_min' => 'nullable|date_format:H:i',
            'lunch_in_max' => 'nullable|date_format:H:i',

            'lunch_out' => 'nullable|date_format:H:i',
            'lunch_out_min' => 'nullable|date_format:H:i',
            'lunch_out_max' => 'nullable|date_format:H:i',

            'ot_in' => 'nullable|date_format:H:i',
            'ot_in_min' => 'nullable|date_format:H:i',
            'ot_in_max' => 'nullable|date_format:H:i',

            'ot_out' => 'nullable|date_format:H:i',
            'ot_out_min' => 'nullable|date_format:H:i',
            'ot_out_max' => 'nullable|date_format:H:i',
        ]);

        $shift->update($validated);

        return redirect()
            ->route('shifts.index')
            ->with('toast', ['type' => 'success', 'message' => 'Shift updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShiftMaster $shift)
    {
        $shift->delete();
        return redirect()
            ->route('shifts.index')
            ->with('toast', ['type' => 'success', 'message' => 'Shift deleted successfully']);
    }
}
