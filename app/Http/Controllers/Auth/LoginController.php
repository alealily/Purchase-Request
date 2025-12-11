<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function create() 
    {
        return view('auth.login'); 
    }

    /**
     * Handle login authentication
     */
    public function store(Request $request)
    {
        // Validate credentials
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Debug log
        \Log::info('Login attempt', [
            'email' => $credentials['email'],
            'user_exists' => \App\Models\User::where('email', $credentials['email'])->exists(),
        ]);

        // Attempt login
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            \Log::info('Login successful', ['user_id' => auth()->id()]);
            
            return redirect()->intended(route('dashboard.index'))
                           ->with('success', 'Welcome back, ' . auth()->user()->name . '!');
        }

        \Log::warning('Login failed', ['email' => $credentials['email']]);

        // Login failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Logout
     */
    public function destroy(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
                       ->with('success', 'You have been logged out.');
    }
}