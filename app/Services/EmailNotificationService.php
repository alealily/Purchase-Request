<?php

namespace App\Services;

use App\Mail\PurchaseRequestApprovalNeededMail;
use App\Mail\PurchaseRequestApprovedMail;
use App\Mail\PurchaseRequestRejectedMail;
use App\Mail\PurchaseRequestRevisionMail;
use App\Mail\PurchaseRequestSubmittedMail;
use App\Models\PurchaseRequest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailNotificationService
{
    /**
     * Send notification to employee when PR is submitted
     */
    public function sendSubmittedNotification(PurchaseRequest $pr): void
    {
        $requester = $pr->user;
        
        if ($requester && $requester->email) {
            try {
                Mail::to($requester->email)->send(new PurchaseRequestSubmittedMail($pr));
                // Add delay to avoid Mailtrap rate limit
                sleep(5);
            } catch (\Exception $e) {
                Log::error('Failed to send submitted notification: ' . $e->getMessage());
            }
        }
    }

    /**
     * Send notification to approver when PR needs approval
     */
    public function sendApprovalNeededNotification(PurchaseRequest $pr, User $approver): void
    {
        if ($approver->email) {
            try {
                Mail::to($approver->email)->send(new PurchaseRequestApprovalNeededMail($pr, $approver));
            } catch (\Exception $e) {
                Log::error('Failed to send approval needed notification: ' . $e->getMessage());
            }
        }
    }

    /**
     * Send notification to employee when PR is approved
     */
    public function sendApprovedNotification(PurchaseRequest $pr, User $approver, bool $isFinalApproval = false): void
    {
        $requester = $pr->user;
        
        if ($requester && $requester->email) {
            try {
                Mail::to($requester->email)->send(new PurchaseRequestApprovedMail($pr, $approver, $isFinalApproval));
                // Add delay to avoid Mailtrap rate limit
                sleep(5);
            } catch (\Exception $e) {
                Log::error('Failed to send approved notification: ' . $e->getMessage());
            }
        }
    }

    /**
     * Send notification to employee when PR is rejected
     */
    public function sendRejectedNotification(PurchaseRequest $pr, User $rejector, string $remarks): void
    {
        $requester = $pr->user;
        
        if ($requester && $requester->email) {
            try {
                Mail::to($requester->email)->send(new PurchaseRequestRejectedMail($pr, $rejector, $remarks));
            } catch (\Exception $e) {
                Log::error('Failed to send rejected notification: ' . $e->getMessage());
            }
        }
    }

    /**
     * Send notification to employee when revision is requested
     */
    public function sendRevisionNotification(PurchaseRequest $pr, User $reviewer, string $remarks): void
    {
        $requester = $pr->user;
        
        if ($requester && $requester->email) {
            try {
                Mail::to($requester->email)->send(new PurchaseRequestRevisionMail($pr, $reviewer, $remarks));
            } catch (\Exception $e) {
                Log::error('Failed to send revision notification: ' . $e->getMessage());
            }
        }
    }
}
