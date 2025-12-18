<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseRequest;
use App\Models\Approval;
use App\Services\ApprovalWorkflowService;
use Barryvdh\DomPDF\Facade\Pdf;

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
    public function index(Request $request)
    {
        $user = auth()->user();
        $userRole = strtolower($user->role ?? '');
        $search = $request->query('search');
        
        // Filter parameters
        $filters = [
            'status' => $request->query('status'),
            'material' => $request->query('material'),
            'supplier' => $request->query('supplier'),
            'user_filter' => $request->query('user_filter'),
            'department' => $request->query('department'),
            'quantity' => $request->query('quantity'),
            'unit_price' => $request->query('unit_price'),
            'total_cost' => $request->query('total_cost'),
            'date_from' => $request->query('date_from'),
            'date_to' => $request->query('date_to'),
        ];
        
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
        
        // Apply search filter if provided
        if ($search) {
            $prQuery->where(function($q) use ($search) {
                $q->where('pr_number', 'like', '%' . $search . '%')
                  ->orWhereHas('prDetails', function($prDetail) use ($search) {
                      $prDetail->where('material_desc', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('prDetails.supplier', function($supplier) use ($search) {
                      $supplier->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // Apply server-side filters
        if ($filters['status']) {
            $prQuery->where('status', 'like', '%' . $filters['status'] . '%');
        }
        if ($filters['material']) {
            $prQuery->whereHas('prDetails', function($q) use ($filters) {
                $q->where('material_desc', 'like', '%' . $filters['material'] . '%');
            });
        }
        if ($filters['supplier']) {
            $prQuery->whereHas('prDetails.supplier', function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['supplier'] . '%');
            });
        }
        if ($filters['user_filter']) {
            $prQuery->whereHas('user', function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['user_filter'] . '%');
            });
        }
        if ($filters['department']) {
            $prQuery->whereHas('user', function($q) use ($filters) {
                $q->where('department', 'like', '%' . $filters['department'] . '%');
            });
        }
        if ($filters['quantity']) {
            $prQuery->whereHas('prDetails', function($q) use ($filters) {
                $q->where('quantity', '>=', (int) $filters['quantity']);
            });
        }
        if ($filters['unit_price']) {
            $prQuery->whereHas('prDetails', function($q) use ($filters) {
                $q->where('unit_price', '>=', (int) $filters['unit_price']);
            });
        }
        if ($filters['total_cost']) {
            $prQuery->whereHas('prDetails', function($q) use ($filters) {
                $q->where('total_cost', '>=', (int) $filters['total_cost']);
            });
        }
        if ($filters['date_from']) {
            $prQuery->whereDate('created_at', '>=', $filters['date_from']);
        }
        if ($filters['date_to']) {
            $prQuery->whereDate('created_at', '<=', $filters['date_to']);
        }
        
        // Get all PRs with ordering and pagination (4 per page)
        $purchaseRequests = $prQuery->orderBy('created_at', 'desc')->paginate(4);
        
        // Preserve search and filters in pagination links
        $purchaseRequests->appends(array_merge(['search' => $search], $filters));
        
        return view('pr_detail.index', compact('purchaseRequests', 'search', 'filters'));
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

    /**
     * Generate PDF document for approved PR
     */
    public function generatePdf(string $id)
    {
        $pr = PurchaseRequest::with(['prDetails.supplier', 'user', 'approvals.user'])
                            ->findOrFail($id);
        
        // Only allow PDF generation for approved PRs
        if (strtolower($pr->status) !== 'approve') {
            return redirect()
                ->route('pr_detail.show', $id)
                ->with('error', 'PDF can only be generated for approved Purchase Requests.');
        }
        
        $user = auth()->user();
        $userRole = strtolower($user->role ?? '');
        $superiorRoles = ['head of department', 'head of division', 'general manager', 'president director'];
        
        // Check access rights (same as show)
        $canView = false;
        if ($userRole === 'it') {
            $canView = true;
        } elseif (in_array($userRole, $superiorRoles)) {
            $canView = $pr->approvals->contains('id_user', $user->id_user);
        } elseif ($pr->id_user === $user->id_user) {
            $canView = true;
        }
        
        if (!$canView) {
            return redirect()
                ->route('pr_detail.index')
                ->with('error', 'You are not authorized to generate PDF for this Purchase Request.');
        }
        
        // Get approval history for signatures
        $approvalHistory = $this->approvalService->getApprovalHistory($pr);
        
        $pdf = Pdf::loadView('pr_detail.pdf', compact('pr', 'approvalHistory'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('PR-' . $pr->pr_number . '.pdf');
    }
}

