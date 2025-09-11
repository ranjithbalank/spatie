<?php
namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use App\Mail\NewInternalJobPosted;
use App\Models\InternalJobPostings;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendInternalJobEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    // The job only needs the job posting model to function.
    protected $internalJob;

    /**
     * Create a new job instance.
     * The job now only accepts the InternalJobPostings model.
     */
    public function __construct(InternalJobPostings $job)
    {
        $this->internalJob = $job;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Step 1: Get all users with the specified email domains
        $emails = User::whereNotNull('email')
            ->where(function ($query) {
                $query->where('email', 'like', '%@dmwindia.com%');
            })
            ->pluck('email')
            ->toArray();

        if (empty($emails)) {
            Log::info('No email addresses found. Cancelling internal job posting notification.');
            return;
        }

        Log::info('Found ' . count($emails) . ' email addresses for internal job posting.');

        // Step 2: Chunk the emails (max 500 per BCC message) and send an email for each chunk.
        // The BCC address is hidden from all recipients.
        collect($emails)->chunk(500)->each(function ($chunk, $index) {
            try {
                // Using a dummy 'to' address is a good practice for BCC-only emails
                // as some SMTP servers require a 'to' address.
                Mail::to('noreply@dmwindia.com')
                    ->bcc($chunk->toArray())
                    ->send(new NewInternalJobPosted($this->internalJob));

                Log::info("Email chunk " . ($index + 1) . " of " . count($chunk) . " recipients successfully sent.");
            } catch (\Exception $e) {
                Log::error('Failed to send internal job email to recipients in chunk ' . ($index + 1) . ': ' . $e->getMessage());
                // Re-throw to mark the job as failed for this attempt
                throw $e;
            }
        });
        
        Log::info('Internal job posting email campaign completed. Total emails sent: ' . count($emails));
    }
}
