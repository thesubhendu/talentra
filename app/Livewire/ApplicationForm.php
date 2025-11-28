<?php

namespace App\Livewire;

use App\Models\JobPost;
use Livewire\Component;
use App\Models\Candidate;
use App\ApplicationStatus;
use App\Models\Application;
use App\Services\NotificationService;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class ApplicationForm extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public ?array $data = [];

    public JobPost $jobPost;

    public function mount(JobPost $jobPost)
    {
        $this->jobPost = $jobPost;
        $this->form->fill();
    }
    
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                    ->required(),
                TextInput::make('last_name')
                    ->required(),
                TextInput::make('email')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->required(),
                Textarea::make('cover_letter')
                    ->required(),
                FileUpload::make('resume')
                    ->disk('public')
                    ->directory('resumes')
                    ->visibility('public')
                    ->required(),
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        $formData = $this->form->getState();
        
        try {
            // Check if candidate already exists by email
            $candidate = Candidate::firstOrCreate(
                ['email' => $formData['email']],
                [
                    'first_name' => $formData['first_name'],
                    'last_name' => $formData['last_name'],
                    'phone' => $formData['phone'],
                ]
            );

            // Check if application already exists for this job and candidate
            $existingApplication = Application::where('job_post_id', $this->jobPost->id)
                ->where('candidate_id', $candidate->id)
                ->first();

            if ($existingApplication) {
                Notification::make()
                    ->title('Application already exists')
                    ->body('You have already applied for this position.')
                    ->warning()
                    ->send();
                return;
            }

            // Create application
            $application = Application::create([
                'job_post_id' => $this->jobPost->id,
                'candidate_id' => $candidate->id,
                'cover_letter' => $formData['cover_letter'],
                'resume_url' => $formData['resume'],
                'status' => ApplicationStatus::PENDING,
            ]);

            // Send email notifications
            $notificationService = new NotificationService();
            $notificationService->sendApplicationSubmittedNotification($application);
            $notificationService->sendNewApplicationNotification($application);

            Notification::make()
                ->title('Application submitted successfully')
                ->body('Thank you for your application. We will review it and get back to you soon.')
                ->success()
                ->send();

            // Reset form after successful submission
            $this->form->fill();
            
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error submitting application')
                ->body('Please try again or contact support if the problem persists.')
                ->danger()
                ->send();

            // Log the error for debugging
            \Log::error('Application submission error', [
                'error' => $e->getMessage(),
                'job_post_id' => $this->jobPost->id,
                'candidate_email' => $formData['email'] ?? 'unknown'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.application-form');
    }
}
