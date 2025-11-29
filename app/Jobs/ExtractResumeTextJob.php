<?php

namespace App\Jobs;

use App\Actions\ExtractResumeTextAction;
use App\Models\Application;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ExtractResumeTextJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 30;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Application $application
    ) {}

    /**
     * Execute the job.
     */
    public function handle(ExtractResumeTextAction $extractResumeTextAction): void
    {
        try {
            // Refresh the application to get latest data
            $this->application->refresh();

            if (empty($this->application->resume_url)) {
                throw new \RuntimeException('Resume URL is not available');
            }

            // Extract text
            $result = $extractResumeTextAction->execute($this->application->resume_url);

            if (! $result->isSuccessful()) {
                throw new \RuntimeException("Failed to extract resume text: {$result->error}");
            }

            // Save extracted text
            $this->application->resume_text = $result->text;
            $this->application->save();

            Log::info('Resume text extracted successfully', [
                'application_id' => $this->application->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Resume text extraction job failed', [
                'application_id' => $this->application->id,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts(),
            ]);

            // Re-throw to trigger retry mechanism
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Resume text extraction job failed permanently', [
            'application_id' => $this->application->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
