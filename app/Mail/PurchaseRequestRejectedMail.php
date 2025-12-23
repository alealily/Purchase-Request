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

class PurchaseRequestRejectedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public PurchaseRequest $purchaseRequest;
    public User $rejector;
    public string $remarks;

    /**
     * Create a new message instance.
     */
    public function __construct(PurchaseRequest $purchaseRequest, User $rejector, string $remarks)
    {
        $this->purchaseRequest = $purchaseRequest;
        $this->rejector = $rejector;
        $this->remarks = $remarks;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Purchase Request #' . $this->purchaseRequest->pr_number . ' Has Been Rejected',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pr-rejected',
            with: [
                'pr' => $this->purchaseRequest,
                'requester' => $this->purchaseRequest->user,
                'rejector' => $this->rejector,
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
