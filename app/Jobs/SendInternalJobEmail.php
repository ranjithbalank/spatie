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

    protected $user;
    protected $internalJob;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, InternalJobPostings $job)
    {
        $this->user = $user;
        $this->internalJob = $job;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // @dd($this->user);
        // Step 1: Get only users with google.co email domains
        $emails = User::whereNotNull('email')
            ->where('email', 'like', '%@google.com%')
            // orwhere('email', 'like', '%@dmw.com%')
            ->orwhere('email', 'like', '%@dmwindia.com%')
            ->pluck('email')
            ->toArray();
        // @dd($emails);
        if (empty($emails)) {
            Log::info('No email addresses found. Cancelling internal job posting notification.');
            return; // Cancel the job execution
        }

        Log::info('Found ' . count($emails) . ' google.co email addresses for internal job posting.');

        // Step 2: Chunk the emails (max 500 per BCC message per Microsoft SMTP)
        collect($emails)->chunk(500)->each(function ($chunk) {
            try {
            //     Mail::to('noreply@yourdomain.com') // dummy "To" address
            //         ->bcc($chunk->toArray())
            //         ->send(new NewInternalJobPosted($this->internalJob));

            //     Log::info('Internal job email sent to ' . count($chunk) . ' google.co recipients.');
            // } catch (\Exception $e) {
            //     Log::error('Failed to send internal job email to google.co users: ' . $e->getMessage());
            //     // Optionally re-throw to mark job as failed
            //     // throw $e;
            // }

            if (str_ends_with($this->user->email, '@dmwindia.com')) {
                Mail::to($this->user->email)
                    ->send(new NewInternalJobPosted($this->internalJob));
            }

            Log::info("Internal job email sent successfully to: {$this->user->email}");

        } catch (\Exception $e) {
            Log::error("Failed to send internal job email to {$this->user->email}: " . $e->getMessage());

            // Re-throw the exception to mark the job as failed
            // This allows Laravel's retry mechanism to work
            throw $e;
        }

        });

        Log::info('Internal job posting email campaign completed. Total google.co emails sent: ' . count($emails));
    }
}
