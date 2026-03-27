<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('companies')->get();
        $companies = Company::all();
        return inertia('User', compact('users', 'companies'));
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
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:5',
            'role' => 'required|in:admin,user'
        ]);

        User::create($request->only('name', 'email', 'password', 'role'));

        return redirect()->route('users.index')->with('toast', ['type' => 'success', 'message' => 'User created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $updatedInfo = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:5',
            'role' => 'required|in:admin,user',
            'is_active' => 'required|bool',
            'selected_companies' => 'nullable|array',
            'selected_companies.*' => 'exists:companies,id'
        ]);

        unset($updatedInfo['selected_companies']);

        if (empty($updatedInfo['password'])) {
            unset($updatedInfo['password']);
        }

        $user->update($updatedInfo);
        $user->companies()->sync($request->selected_companies ?? []);

        return redirect()->route('users.index')->with('toast', ['type' => 'success', 'message' => 'User updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('toast', ['type' => 'success', 'message' => 'User deleted successfully']);
    }
}
