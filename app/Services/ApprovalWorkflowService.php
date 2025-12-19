<?php

namespace App\Services;

use App\Models\Approval;
use App\Models\PurchaseRequest;
use App\Models\User;

class ApprovalWorkflowService
{
    /**
     * Determine approval chain based on division and total cost
     * 
     * @param PurchaseRequest $pr
     * @return array Array of approval levels with position and criteria
     */
    public function determineApprovalChain(PurchaseRequest $pr): array
    {
        $creator = $pr->user;
        $totalCost = $pr->prDetails->total_cost ?? 0;
        $division = $creator->division;
        
        // Threshold: Rp 25.000.000
        $costThreshold = 25000000;
        
        $chain = [];
        
        // Level 1: Always Head of Department (same department as creator)
        $chain[] = [
            'level' => 1,
            'position' => 'head_of_department',
            'department' => $creator->department,
            'division' => $creator->division,
        ];
        
        // Level 2 and beyond: Based on division and total cost
        if ($creator->isGeneralDivision()) {
            // General Division Flow: Head Dept → Head Div → President Director
            $chain[] = [
                'level' => 2,
                'position' => 'head_of_division',
                'division' => 'General',
            ];
            
            $chain[] = [
                'level' => 3,
                'position' => 'president_director',
            ];
            
        } elseif ($creator->isFactoryDivision()) {
            // Factory Division Flow: Head Dept → Head Div (Factory Manager)
            $chain[] = [
                'level' => 2,
                'position' => 'head_of_division',
                'division' => $creator->division, // PCBA, ASSY 1, etc.
            ];
            
            // If total cost > Rp 25.000.000, add Head of Division General and President Director
            if ($totalCost > $costThreshold) {
                $chain[] = [
                    'level' => 3,
                    'position' => 'head_of_division',
                    'division' => 'General', // HODiv of General division (General Manager)
                ];
                
                $chain[] = [
                    'level' => 4,
                    'position' => 'president_director',
                ];
            }
            // If total cost <= Rp 25.000.000, final approval at Head of Division (Factory Manager)
        }
        
        return $chain;
    }
    
    /**
     * Get approver user for specific level configuration
     * 
     * @param array $levelConfig
     * @return User|null
     */
    public function getApproverForLevel(array $levelConfig): ?User
    {
        // Find user by position (not role)
        $query = User::where('position', $levelConfig['position']);
        
        if (isset($levelConfig['department'])) {
            $query->where('department', $levelConfig['department']);
        }
        
        if (isset($levelConfig['division'])) {
            $query->where('division', $levelConfig['division']);
        }
        
        return $query->first();
    }
    
    /**
     * Create approval chain records for Purchase Request
     * 
     * @param PurchaseRequest $pr
     * @return void
     */
    public function createApprovalChain(PurchaseRequest $pr): void
    {
        $chain = $this->determineApprovalChain($pr);
        
        foreach ($chain as $levelConfig) {
            $approver = $this->getApproverForLevel($levelConfig);
            
            if ($approver) {
                Approval::create([
                    'id_pr' => $pr->id_pr,
                    'id_user' => $approver->id_user,
                    'level' => $levelConfig['level'],
                    'approval_status' => 'pending',
                ]);
            }
        }
    }
    
    /**
     * Get current pending approval for PR
     * 
     * @param PurchaseRequest $pr
     * @return Approval|null
     */
    public function getCurrentPendingApproval(PurchaseRequest $pr): ?Approval
    {
        return $pr->approvals()
                  ->where('approval_status', 'pending')
                  ->orderBy('level')
                  ->first();
    }
    
    /**
     * Check if user can approve this PR
     * 
     * @param User $user
     * @param PurchaseRequest $pr
     * @return bool
     */
    public function canUserApprove(User $user, PurchaseRequest $pr): bool
    {
        $currentApproval = $this->getCurrentPendingApproval($pr);
        
        if (!$currentApproval) {
            return false;
        }
        
        return $currentApproval->id_user === $user->id_user;
    }
    
    /**
     * Approve Purchase Request
     * 
     * @param PurchaseRequest $pr
     * @param User $approver
     * @param string|null $remarks
     * @return bool
     */
    public function approvePR(PurchaseRequest $pr, User $approver, ?string $remarks = null): bool
    {
        if (!$this->canUserApprove($approver, $pr)) {
            return false;
        }
        
        $currentApproval = $this->getCurrentPendingApproval($pr);
        
        // Update current approval
        $currentApproval->update([
            'approval_status' => 'approve',
            'approval_date' => now(),
            'remarks' => $remarks,
        ]);
        
        // Check if this is the final approval
        $hasMorePendingApprovals = $pr->approvals()
                                       ->where('approval_status', 'pending')
                                       ->exists();
        
        if (!$hasMorePendingApprovals) {
            // Final approval - update PR status
            $pr->update(['status' => 'approve']);
        }
        
        return true;
    }
    
    /**
     * Reject Purchase Request
     * 
     * @param PurchaseRequest $pr
     * @param User $approver
     * @param string $remarks
     * @return bool
     */
    public function rejectPR(PurchaseRequest $pr, User $approver, string $remarks): bool
    {
        if (!$this->canUserApprove($approver, $pr)) {
            return false;
        }
        
        $currentApproval = $this->getCurrentPendingApproval($pr);
        
        // Update current approval
        $currentApproval->update([
            'approval_status' => 'reject',
            'approval_date' => now(),
            'remarks' => $remarks,
        ]);
        
        // Update PR status to rejected
        $pr->update(['status' => 'reject']);
        
        // Cancel all pending approvals
        $pr->approvals()
           ->where('approval_status', 'pending')
           ->update(['approval_status' => 'cancelled']);
        
        return true;
    }
    
    /**
     * Request revision for Purchase Request
     * 
     * @param PurchaseRequest $pr
     * @param User $approver
     * @param string $remarks
     * @return bool
     */
    public function revisionPR(PurchaseRequest $pr, User $approver, string $remarks): bool
    {
        if (!$this->canUserApprove($approver, $pr)) {
            return false;
        }
        
        $currentApproval = $this->getCurrentPendingApproval($pr);
        
        // Update current approval
        $currentApproval->update([
            'approval_status' => 'revision',
            'approval_date' => now(),
            'remarks' => $remarks,
        ]);
        
        // Update PR status to revision
        $pr->update(['status' => 'revision']);
        
        // Cancel all pending approvals (akan dibuat ulang saat user submit revision)
        $pr->approvals()
           ->where('approval_status', 'pending')
           ->update(['approval_status' => 'cancelled']);
        
        return true;
    }
    
    /**
     * Get approval history for PR (formatted for view)
     * 
     * @param PurchaseRequest $pr
     * @return array
     */
    public function getApprovalHistory(PurchaseRequest $pr): array
    {
        // Get all non-cancelled approvals ordered by creation (id_approval)
        // This ensures history is shown chronologically including past revision rounds
        $approvals = $pr->approvals()
                       ->with('user')
                       ->where('approval_status', '!=', 'cancelled')
                       ->orderBy('id_approval')
                       ->get();
        
        $history = [];
        
        foreach ($approvals as $approval) {
            // Map position to readable role name
            $roleNames = [
                'head_of_department' => 'HEAD OF DEPARTMENT',
                'head_of_division' => 'HEAD OF DIVISION',
                'general_manager' => 'GENERAL MANAGER',
                'president_director' => 'PRESIDENT DIRECTOR',
            ];
            
            $user = $approval->user;
            
            // Use department if available, otherwise use division
            $deptOrDiv = (!empty($user->department) && $user->department !== '-') 
                        ? $user->department 
                        : ($user->division ?? '-');
            
            $history[] = [
                'user' => $user->name ?? 'Unknown',
                'role' => $roleNames[$user->position ?? ''] ?? strtoupper($user->role ?? 'Unknown'),
                'department' => $deptOrDiv,
                'division' => $user->division ?? '-',
                'status' => $approval->approval_status,
                'remarks' => $approval->remarks,
                'signature' => $user->signature ?? null,
                'date' => $approval->approval_date 
                    ? \Carbon\Carbon::parse($approval->approval_date)->format('d M Y, H:i')
                    : null,
            ];
        }
        
        return $history;
    }
}
