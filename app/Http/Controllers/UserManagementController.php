<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Signature;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users with pagination, optional role filter, and search.
     */
    public function index(Request $request)
    {
        $role = $request->query('role');
        $search = $request->query('search');
        
        $query = User::with('signature');
        
        // Filter by role if specified
        if ($role && $role !== 'All') {
            $query->where('role', $role);
        }
        
        // Search by name, email, badge, or department
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('badge', 'like', '%' . $search . '%')
                  ->orWhere('department', 'like', '%' . $search . '%')
                  ->orWhere('division', 'like', '%' . $search . '%');
            });
        }
        
        $users = $query->orderBy('id_user', 'desc')->paginate(4);
        
        // Preserve query parameters in pagination links
        $users->appends(['role' => $role, 'search' => $search]);
        
        return view('user.index', compact('users', 'role', 'search'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'badge' => 'required|string|max:50|unique:users,badge',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string',
            'department' => 'required|string|max:255',
            'division' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'signature' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Map role to position
        $position = $this->mapRoleToPosition($validated['role']);

        // Create user
        $user = User::create([
            'name' => ucwords(strtolower($validated['name'])),
            'badge' => strtoupper($validated['badge']),
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'position' => $position,
            'department' => isset($validated['department']) ? ucwords(strtolower($validated['department'])) : '-',
            'division' => $validated['division'] ?? '-',
            'is_active' => true,
        ]);

        // Handle signature upload
        if ($request->hasFile('signature')) {
            $path = $request->file('signature')->store('signatures', 'public');
            Signature::create([
                'id_user' => $user->id_user,
                'file_path' => $path,
            ]);
        }

        return redirect()
            ->route('user_management.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Display the specified user.
     */
    public function show(string $id)
    {
        $user = User::with('signature')->findOrFail($id);
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(string $id)
    {
        $user = User::with('signature')->findOrFail($id);
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'badge' => 'required|string|max:50|unique:users,badge,' . $id . ',id_user',
            'email' => 'required|email|unique:users,email,' . $id . ',id_user',
            'role' => 'required|string',
            'department' => 'nullable|string|max:255',
            'division' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        // Map role to position
        $position = $this->mapRoleToPosition($validated['role']);

        // Update user
        $user->update([
            'name' => ucwords(strtolower($validated['name'])),
            'badge' => strtoupper($validated['badge']),
            'email' => $validated['email'],
            'role' => $validated['role'],
            'position' => $position,
            'department' => isset($validated['department']) ? ucwords(strtolower($validated['department'])) : '-',
            'division' => $validated['division'] ?? '-',
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        // Update password if provided
        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        // Handle signature upload
        if ($request->hasFile('signature')) {
            // Delete old signature if exists
            if ($user->signature) {
                Storage::disk('public')->delete($user->signature->file_path);
                $user->signature->delete();
            }
            
            $path = $request->file('signature')->store('signatures', 'public');
            Signature::create([
                'id_user' => $user->id_user,
                'file_path' => $path,
            ]);
        }

        return redirect()
            ->route('user_management.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting yourself
        if ($user->id_user === auth()->id()) {
            return redirect()
                ->route('user_management.index')
                ->with('error', 'You cannot delete your own account');
        }

        // Delete signature if exists
        if ($user->signature) {
            Storage::disk('public')->delete($user->signature->file_path);
            $user->signature->delete();
        }

        $user->delete();

        return redirect()
            ->route('user_management.index')
            ->with('success', 'User deleted successfully');
    }

    /**
     * Map role to position value
     */
    private function mapRoleToPosition(string $role): string
    {
        $roleMap = [
            'Employee' => 'staff',
            'IT' => 'staff',
            'Head of Department' => 'head_of_department',
            'Head of Division' => 'head_of_division',
            'President Director' => 'president_director',
        ];

        return $roleMap[$role] ?? 'staff';
    }

    /**
     * Export users to Excel
     */
    public function export()
    {
        date_default_timezone_set('Asia/Jakarta');
        $filename = 'UserManagement_' . date('dmY_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\UserExport, $filename);
    }
}
