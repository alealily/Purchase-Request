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

class PurchaseRequestApprovalNeededMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public PurchaseRequest $purchaseRequest;
    public User $approver;

    /**
     * Create a new message instance.
     */
    public function __construct(PurchaseRequest $purchaseRequest, User $approver)
    {
        $this->purchaseRequest = $purchaseRequest;
        $this->approver = $approver;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $requesterName = $this->purchaseRequest->user->name ?? 'An employee';
        
        return new Envelope(
            subject: 'Approval Needed: Purchase Request #' . $this->purchaseRequest->pr_number . ' from ' . $requesterName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pr-approval-needed',
            with: [
                'pr' => $this->purchaseRequest,
                'requester' => $this->purchaseRequest->user,
                'approver' => $this->approver,
                'prDetails' => $this->purchaseRequest->prDetails,
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
