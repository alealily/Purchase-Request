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
        // Validate credentials including role
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|string',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        $selectedRole = strtolower($request->role);

        // Debug log
        \Log::info('Login attempt', [
            'email' => $credentials['email'],
            'selected_role' => $selectedRole,
            'user_exists' => \App\Models\User::where('email', $credentials['email'])->exists(),
        ]);

        // Attempt login
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Check if user is active
            $user = auth()->user();
            
            if (!$user->is_active) {
                // User is inactive - logout and return error
                Auth::logout();
                $request->session()->invalidate();
                
                \Log::warning('Login failed - user inactive', [
                    'email' => $credentials['email'],
                    'user_id' => $user->id_user,
                ]);
                
                return back()->withErrors([
                    'email' => 'Your account has been deactivated. Please contact IT administrator.',
                ])->onlyInput('email', 'role');
            }
            
            // Check if selected role matches user's actual role
            $userRole = strtolower($user->role ?? '');
            
            if ($userRole !== $selectedRole) {
                // Role mismatch - logout and return error
                Auth::logout();
                $request->session()->invalidate();
                
                \Log::warning('Login failed - role mismatch', [
                    'email' => $credentials['email'],
                    'selected_role' => $selectedRole,
                    'actual_role' => $userRole,
                ]);
                
                return back()->withErrors([
                    'role' => 'The selected role does not match your account. Please select the correct role.',
                ])->onlyInput('email', 'role');
            }
            
            $request->session()->regenerate();
            
            \Log::info('Login successful', ['user_id' => auth()->id()]);
            
            return redirect()->intended(route('dashboard.index'))
                           ->with('success', 'Welcome back, ' . auth()->user()->name . '!');
        }

        \Log::warning('Login failed', ['email' => $credentials['email']]);

        // Login failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email', 'role');
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