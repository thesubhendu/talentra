<?php

namespace App\Jobs;

use App\Models\Application;
use App\Services\ScreeningService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessApplicationScreeningJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Application $application
    ) {}

    /**
     * Execute the job.
     */
    public function handle(ScreeningService $screeningService): void
    {
        try {
            // Refresh the application to get latest data
            $this->application->refresh();

            // Process the screening pipeline
            $success = $screeningService->processApplication($this->application);

            if (! $success) {
                throw new \RuntimeException('Screening process failed');
            }

            Log::info('Application screening completed successfully', [
                'application_id' => $this->application->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Application screening job failed', [
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
        Log::error('Application screening job failed permanently', [
            'application_id' => $this->application->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
