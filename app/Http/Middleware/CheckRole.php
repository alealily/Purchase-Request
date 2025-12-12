<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Superior role aliases - these roles are considered as 'superior'
     */
    private const SUPERIOR_ROLES = [
        'superior',
        'head of department',
        'head of division',
        'president director',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Get user's role (lowercase for comparison)
        $userRole = strtolower(auth()->user()->role ?? '');

        // Expand 'superior' to include all superior roles
        $allowedRoles = [];
        foreach ($roles as $role) {
            $roleLower = strtolower($role);
            if ($roleLower === 'superior') {
                // If 'superior' is in allowed roles, add all superior role aliases
                $allowedRoles = array_merge($allowedRoles, self::SUPERIOR_ROLES);
            } else {
                $allowedRoles[] = $roleLower;
            }
        }

        // Check if user's role is in the allowed roles
        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
