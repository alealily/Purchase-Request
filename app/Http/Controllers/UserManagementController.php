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
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('signature')->get();
        return view('user.index', compact('users'));
    }

    /**
     * Get all users as JSON for AJAX
     */
    public function getUsers()
    {
        $users = User::with('signature')->get()->map(function ($user) {
            return [
                'id_user' => $user->id_user,
                'name' => $user->name,
                'badge' => $user->badge,
                'email' => $user->email,
                'role' => $user->role,
                'position' => $user->position,
                'department' => $user->department,
                'division' => $user->division,
                'is_active' => $user->is_active,
                'signature' => $user->signature ? Storage::url($user->signature->file_path) : null,
            ];
        });
        
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'badge' => 'required|string|max:50|unique:users,badge',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string',
            'department' => 'nullable|string|max:255',
            'division' => 'nullable|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Map role to position
        $position = $this->mapRoleToPosition($validated['role']);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'badge' => $validated['badge'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'position' => $position,
            'department' => $validated['department'] ?? '-',
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

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
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
            'name' => $validated['name'],
            'badge' => $validated['badge'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'position' => $position,
            'department' => $validated['department'] ?? '-',
            'division' => $validated['division'] ?? '-',
            'is_active' => $request->has('is_active') ? $validated['is_active'] : $user->is_active,
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

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'user' => $user,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting yourself
        if ($user->id_user === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account',
            ], 403);
        }

        // Delete signature if exists
        if ($user->signature) {
            Storage::disk('public')->delete($user->signature->file_path);
            $user->signature->delete();
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
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
}
