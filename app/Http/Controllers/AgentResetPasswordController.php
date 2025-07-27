<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AgentResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        return view('agent.password.reset', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:school_agents,email',
            'password' => 'required|confirmed|min:8',
        ], [
            'email.exists' => 'We can’t find a user with that email address.',
            'password.confirmed' => 'The password confirmation does not match.',
        ]);

        $status = Password::broker('school_agents')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($agent, $password) {
                $agent->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                event(new PasswordReset($agent));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect('/unified-login')->with('status', __($status))
            : back()->withInput($request->only('email'))
                    ->withErrors(['email' => [__($status)]]);
    }
}
