<?php

namespace App\Livewire;

use App\Models\JobPost;
use Livewire\Component;
use App\Models\Candidate;
use App\ApplicationStatus;
use App\Models\Application;
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
        // create candidate
        $candidate = Candidate::create([
            'first_name' => $formData['first_name'],
            'last_name' => $formData['last_name'],
            'email' => $formData['email'],
            'phone' => $formData['phone'],
        ]);

        // create application
        Application::create([
            'job_post_id' => $this->jobPost->id,
            'candidate_id' => $candidate->id,
            'cover_letter' => $formData['cover_letter'],
            'resume_url' => $formData['resume'],
            'status' => ApplicationStatus::PENDING,
        ]);

        Notification::make()
            ->title('Application submitted successfully')
            ->success()
            ->send();
    }

    public function render()
    {
        return view('livewire.application-form');
    }
}
