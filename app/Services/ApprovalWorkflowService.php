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
        
        $chain = [];
        
        // Level 1: Always Head of Department
        $chain[] = [
            'level' => 1,
            'position' => 'head_of_department',
            'department' => $creator->department,
        ];
        
        // Level 2 and beyond: Based on division and total cost
        if ($creator->isGeneralDivision()) {
            // General Division Flow: Head Dept → Head Div General → President Dir
            $chain[] = [
                'level' => 2,
                'position' => 'head_of_division',
                'division' => 'general',
            ];
            
            $chain[] = [
                'level' => 3,
                'position' => 'president_director',
            ];
            
        } elseif ($creator->isFactoryDivision()) {
            // Factory Division Flow
            $chain[] = [
                'level' => 2,
                'position' => 'head_of_division',
                'division' => $creator->division, // PCBA, ASSY 1, etc.
            ];
            
            // If total cost > $1500, add GM and President Director
            if ($totalCost > 1500) {
                $chain[] = [
                    'level' => 3,
                    'position' => 'head_of_division',
                    'division' => 'general', // GM (Head of Division General)
                ];
                
                $chain[] = [
                    'level' => 4,
                    'position' => 'president_director',
                ];
            }
            // If total cost <= $1500, stop at Head of Division (Factory)
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
        $query = User::where('role', 'superior')
                    ->where('position', $levelConfig['position']);
        
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
                    'id_user' => $approver->id,
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
        
        return $currentApproval->id_user === $user->id;
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
     * Get approval history for PR
     * 
     * @param PurchaseRequest $pr
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getApprovalHistory(PurchaseRequest $pr)
    {
        return $pr->approvals()
                  ->with('user')
                  ->orderBy('level')
                  ->get();
    }
}
