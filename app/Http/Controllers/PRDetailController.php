<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseRequest;
use App\Models\Approval;
use App\Services\ApprovalWorkflowService;

class PRDetailController extends Controller
{
    protected $approvalService;

    public function __construct(ApprovalWorkflowService $approvalService)
    {
        $this->approvalService = $approvalService;
    }

    /**
     * Display a listing of PRs based on role and approval level.
     * 
     * - Approvers (HOD, HODiv, GM, PresDir): Only see PRs waiting for THEIR approval
     * - IT: Can see ALL PRs
     * - Employee: Can see their own PRs (for tracking)
     */
    public function index()
    {
        $user = auth()->user();
        $userRole = strtolower($user->role ?? '');
        
        // Build query based on role
        $prQuery = PurchaseRequest::with(['prDetails.supplier', 'user', 'approvals']);
        
        if ($userRole === 'it') {
            // IT: Can see ALL PRs
            // No filter needed
        } elseif (in_array($userRole, ['head of department', 'head of division', 'general manager', 'president director'])) {
            // Approvers: Show PRs where:
            // 1. User has already actioned (approve/reject/revision) - for tracking
            // 2. OR User is the current pending approver (their turn)
            
            // Get PRs user has already actioned
            $actionedPrIds = Approval::where('id_user', $user->id_user)
                                     ->whereIn('approval_status', ['approve', 'approved', 'reject', 'rejected', 'revision'])
                                     ->pluck('id_pr')
                                     ->toArray();
            
            // Get PRs where user is current pending approver
            $pendingApprovals = Approval::where('id_user', $user->id_user)
                                        ->where('approval_status', 'pending')
                                        ->get();
            
            $currentTurnPrIds = [];
            foreach ($pendingApprovals as $approval) {
                $minPendingLevel = (int) Approval::where('id_pr', $approval->id_pr)
                                                  ->where('approval_status', 'pending')
                                                  ->min('level');
                if ((int) $approval->level === $minPendingLevel) {
                    $currentTurnPrIds[] = $approval->id_pr;
                }
            }
            
            // Combine both sets
            $prIds = array_unique(array_merge($actionedPrIds, $currentTurnPrIds));
            
            if (empty($prIds)) {
                $prQuery->whereRaw('1 = 0'); // Return empty if no PRs match
            } else {
                $prQuery->whereIn('id_pr', $prIds);
            }
        } else {
            // Employee: Can see their own PRs only
            $prQuery->where('id_user', $user->id_user);
        }
        
        // Get all PRs with ordering
        $purchaseRequests = $prQuery->orderBy('created_at', 'desc')->get();
        
        return view('pr_detail.index', compact('purchaseRequests'));
    }

    /**
     * Display the specified PR for approval (read-only view for approvers)
     */
    public function show(string $id)
    {
        $pr = PurchaseRequest::with(['prDetails.supplier', 'user', 'approvals.user'])
                            ->findOrFail($id);
        
        $user = auth()->user();
        $userRole = strtolower($user->role ?? '');
        $superiorRoles = ['head of department', 'head of division', 'general manager', 'president director'];
        
        // Check access rights
        $canView = false;
        
        // IT can view all PRs
        if ($userRole === 'it') {
            $canView = true;
        }
        // Superiors can view if they are an approver for this PR
        elseif (in_array($userRole, $superiorRoles)) {
            $canView = $pr->approvals->contains('id_user', $user->id_user);
        }
        // Employee can view their own PRs
        elseif ($pr->id_user === $user->id_user) {
            $canView = true;
        }
        
        if (!$canView) {
            return redirect()
                ->route('pr_detail.index')
                ->with('error', 'You are not authorized to view this Purchase Request.');
        }
        
        $approvalHistory = $this->approvalService->getApprovalHistory($pr);
        $canApprove = $this->approvalService->canUserApprove($user, $pr);
        
        return view('pr_detail.show', compact('pr', 'approvalHistory', 'canApprove'));
    }
}

