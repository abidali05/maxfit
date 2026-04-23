<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoachAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.coaches-login'); // Create view
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Custom guard for coaches
        if (Auth::guard('coach')->attempt($credentials, $request->filled('remember'))) {
            return redirect()->route('coach.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('coach')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('coaches.login');
    }
}
