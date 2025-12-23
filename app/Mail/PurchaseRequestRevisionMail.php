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

class PurchaseRequestRevisionMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public PurchaseRequest $purchaseRequest;
    public User $reviewer;
    public string $remarks;

    /**
     * Create a new message instance.
     */
    public function __construct(PurchaseRequest $purchaseRequest, User $reviewer, string $remarks)
    {
        $this->purchaseRequest = $purchaseRequest;
        $this->reviewer = $reviewer;
        $this->remarks = $remarks;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Revision Requested: Purchase Request #' . $this->purchaseRequest->pr_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pr-revision',
            with: [
                'pr' => $this->purchaseRequest,
                'requester' => $this->purchaseRequest->user,
                'reviewer' => $this->reviewer,
                'prDetails' => $this->purchaseRequest->prDetails,
                'remarks' => $this->remarks,
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
