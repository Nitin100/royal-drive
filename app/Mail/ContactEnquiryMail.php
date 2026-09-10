<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactEnquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $name,
        public string $email,
        public ?string $phone,
        public ?string $subject,
        public string $message,
    ) {}

    public function build(): self
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->replyTo($this->email, $this->name)
            ->subject('New Contact Enquiry: ' . ($this->subject ?: 'General enquiry'))
            ->markdown('emails.contact-enquiry');
    }
}
