<?php

use Illuminate\Support\Facades\Mail;

// Test sending email via Gmail SMTP
Mail::raw('This is a test email from Laravel Purchase Request System.', function($message) {
    $message->to('digitamobile09@gmail.com')
            ->subject('Test Email from Laravel');
});

echo "Email sent successfully!\n";
