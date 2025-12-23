<?php

namespace App\Mail;

use App\Models\PurchaseRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PurchaseRequestApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public PurchaseRequest $purchaseRequest;
    public User $approver;
    public bool $isFinalApproval;

    /**
     * Create a new message instance.
     */
    public function __construct(PurchaseRequest $purchaseRequest, User $approver, bool $isFinalApproval = false)
    {
        $this->purchaseRequest = $purchaseRequest;
        $this->approver = $approver;
        $this->isFinalApproval = $isFinalApproval;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->isFinalApproval
            ? 'Congratulations! Purchase Request #' . $this->purchaseRequest->pr_number . ' Fully Approved'
            : 'Purchase Request #' . $this->purchaseRequest->pr_number . ' Approved by ' . $this->approver->name;
        
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pr-approved',
            with: [
                'pr' => $this->purchaseRequest,
                'requester' => $this->purchaseRequest->user,
                'approver' => $this->approver,
                'prDetails' => $this->purchaseRequest->prDetails,
                'isFinalApproval' => $this->isFinalApproval,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
