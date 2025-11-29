<?php

namespace App\Observers;

use App\Jobs\ProcessApplicationScreeningJob;
use App\Models\Application;
use App\Services\NotificationService;

class ApplicationObserver
{
    /**
     * Handle the Application "created" event.
     */
    public function created(Application $application): void
    {
        // Dispatch screening job asynchronously
        ProcessApplicationScreeningJob::dispatch($application);
    }

    /**
     * Handle the Application "updated" event.
     */
    public function updated(Application $application): void
    {
        // Check if status was changed
        if ($application->isDirty('status')) {
            $notificationService = new NotificationService;
            $notificationService->sendApplicationStatusChangedNotification($application);
        }
    }

    /**
     * Handle the Application "deleted" event.
     */
    public function deleted(Application $application): void
    {
        //
    }

    /**
     * Handle the Application "restored" event.
     */
    public function restored(Application $application): void
    {
        //
    }

    /**
     * Handle the Application "force deleted" event.
     */
    public function forceDeleted(Application $application): void
    {
        //
    }
}
