<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseRequest;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userRole = strtolower($user->role ?? '');
        
        // Build query based on role visibility
        $prQuery = PurchaseRequest::with(['prDetails', 'user']);
        
        if (in_array($userRole, ['president director', 'it'])) {
            // President Director & IT: can see ALL PRs
            // No filter needed
        } elseif ($userRole === 'head of division') {
            // Head of Division: can see PRs from users in same division
            $prQuery->whereHas('user', function ($q) use ($user) {
                $q->where('division', $user->division);
            });
        } elseif ($userRole === 'head of department') {
            // Head of Department: can see PRs from users in same department
            $prQuery->whereHas('user', function ($q) use ($user) {
                $q->where('department', $user->department);
            });
        } else {
            // Employee: can ONLY see their OWN PRs (for privacy)
            $prQuery->where('id_user', $user->id_user);
        }
        
        // Get counts for cards
        $pendingCount = (clone $prQuery)->where('status', 'pending')->count();
        $approvedCount = (clone $prQuery)->whereIn('status', ['approve', 'approved'])->count();
        $rejectedCount = (clone $prQuery)->whereIn('status', ['reject', 'rejected'])->count();
        
        // Get PRs with pagination (4 per page)
        $recentPRs = $prQuery->orderBy('created_at', 'desc')
                            ->paginate(4);
        
        return view('dashboard.index', compact(
            'pendingCount', 
            'approvedCount', 
            'rejectedCount', 
            'recentPRs'
        ));
    }
}

