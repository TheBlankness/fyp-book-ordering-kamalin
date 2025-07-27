<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentLoginController extends Controller
{
    /**
     * Show the login form for school agents.
     */
    public function showLoginForm()
    {
        return view('agent.login');
    }

    /**
     * Handle the login request for school agents.
     */
    public function login(Request $request)
    {
        // Validate input with custom error messages
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username is required.',
            'password.required' => 'Password is required.',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::guard('agent')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('agent.dashboard'));
        }

        // Failed login – pass back 'login_type' to keep the agent tab active
        return redirect()->back()->withInput($request->only('username', 'login_type'))->withErrors([
            'login' => 'Invalid username or password.',
        ]);
    }

    /**
     * Logout the school agent.
     */
    public function logout(Request $request)
    {
        Auth::guard('agent')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/unified-login')->with('status', 'You have been logged out.');
    }
}
