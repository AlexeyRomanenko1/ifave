<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Message;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $email;
    public $message;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @param string $name
     * @param string $email
     * @param string $message
     * @param string $subject
     */
    public function __construct($name, $email, $message, $subject)
    {
        $this->name = $name;
        $this->email = $email;
        $this->message = $message;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.contact')
            ->subject('New Contact Form Submission')
            ->with([
                'name' => $this->name,
                'email' => $this->email,
                'message' => $this->message,
            ])
            ->replyTo($this->email, $this->name);
    }
    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: "$this->subject",
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  */
    // public function content(): Content
    // {
    //     return new Content(
    //         text: 'From' . $this->email . '<br>' . 'Name' . $this->name . $this->message
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
