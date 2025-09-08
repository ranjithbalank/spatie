<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewInternalJobPosted extends Mailable
{
    use Queueable, SerializesModels;

    public $job;

    public function __construct($job)
    {
        $this->job = $job;
    }

    public function build()
    {
        return $this->subject('New Internal Job Posted: ' . $this->job->job_title)
                    ->view('emails.internal_job_posted');
    }
}
