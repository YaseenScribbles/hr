<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate the user

        /** @var \Illuminate\Contracts\Auth\StatefulGuard $auth */

        $auth = auth();

        if ($auth->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('employees.index')->with('toast', ['type' => 'success', 'message' => 'Login success']);
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

public function logout(Request $request)
{

    /** @var \Illuminate\Contracts\Auth\StatefulGuard $auth */
    $auth = auth();
    $auth->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login')
                     ->with('success', 'Logged out successfully');
}
}
