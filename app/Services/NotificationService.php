<?php

namespace App\Services;

use App\Mail\ApplicationSubmitted;
use App\Mail\ApplicationStatusChanged;
use App\Mail\NewApplicationNotification;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send application submitted notification to candidate
     */
    public function sendApplicationSubmittedNotification(Application $application): void
    {
        try {
            $candidate = $application->candidate;
            $jobPost = $application->jobPost;

            Mail::to($candidate->email)->send(
                new ApplicationSubmitted($application, $candidate, $jobPost)
            );

            Log::info('Application submitted email sent', [
                'candidate_email' => $candidate->email,
                'job_title' => $jobPost->title,
                'application_id' => $application->id
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send application submitted email', [
                'error' => $e->getMessage(),
                'application_id' => $application->id
            ]);
        }
    }

    /**
     * Send application status change notification to candidate
     */
    public function sendApplicationStatusChangedNotification(Application $application): void
    {
        try {
            $candidate = $application->candidate;
            $jobPost = $application->jobPost;

            Mail::to($candidate->email)->send(
                new ApplicationStatusChanged($application, $candidate, $jobPost)
            );

            Log::info('Application status changed email sent', [
                'candidate_email' => $candidate->email,
                'job_title' => $jobPost->title,
                'new_status' => $application->status->value,
                'application_id' => $application->id
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send application status changed email', [
                'error' => $e->getMessage(),
                'application_id' => $application->id
            ]);
        }
    }

    /**
     * Send new application notification to HR/recruiters
     */
    public function sendNewApplicationNotification(Application $application): void
    {
        try {
            $candidate = $application->candidate;
            $jobPost = $application->jobPost;

            // Get HR/admin email addresses (you can configure this in config or database)
            $hrEmails = $this->getHREmails();

            foreach ($hrEmails as $email) {
                Mail::to($email)->send(
                    new NewApplicationNotification($application, $candidate, $jobPost)
                );
            }

            Log::info('New application notification sent to HR', [
                'candidate_name' => $candidate->first_name . ' ' . $candidate->last_name,
                'job_title' => $jobPost->title,
                'hr_emails' => $hrEmails,
                'application_id' => $application->id
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send new application notification to HR', [
                'error' => $e->getMessage(),
                'application_id' => $application->id
            ]);
        }
    }

    /**
     * Get HR email addresses from configuration or database
     */
    private function getHREmails(): array
    {
        // You can configure this in config/mail.php or fetch from database
        $configEmails = config('mail.hr_emails', []);
        
        if (!empty($configEmails)) {
            return $configEmails;
        }

        $adminEmails = User::where('email', 'like', '%admin%')
            ->orWhere('email', 'like', '%hr%')
            ->pluck('email')
            ->toArray();

        // If no admin emails found, use the default admin email
        if (empty($adminEmails)) {
            return [config('mail.from.address', 'admin@example.com')];
        }

        return $adminEmails;
    }

    /**
     * Send bulk notifications (useful for status updates)
     */
    public function sendBulkStatusChangeNotifications(array $applicationIds, string $newStatus): void
    {
        $applications = Application::with(['candidate', 'jobPost'])
            ->whereIn('id', $applicationIds)
            ->get();

        foreach ($applications as $application) {
            $this->sendApplicationStatusChangedNotification($application);
        }

        Log::info('Bulk status change notifications sent', [
            'application_count' => count($applicationIds),
            'new_status' => $newStatus
        ]);
    }
}
