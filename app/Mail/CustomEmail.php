<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use App\Models\User;

class CustomEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $message;
    public $sender;

    public function __construct($subject, $message, User $sender)
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->sender = $sender;
    }

public function envelope()
{
    return new Envelope(
        subject: $this->subject,
        from: new Address(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')),
    );
}

    public function content()
    {
        return new Content(
            view: 'superadmin.custom',
            with: [
                'subject' => $this->subject,
                'messageContent' => $this->message,
                'sender' => $this->sender,
            ],
        );
    }
}