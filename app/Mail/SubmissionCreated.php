<?php

namespace App\Mail;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmissionCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $submission;

    public function __construct(Submission $submission)
    {
        $this->submission = $submission;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengajuan Penelitian Berhasil: ' . $this->submission->registration_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.submission_created',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
