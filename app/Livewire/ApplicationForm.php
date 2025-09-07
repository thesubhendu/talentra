<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\JobPost;

class ApplicationForm extends Component
{
    public JobPost $jobPost;

    public function mount(JobPost $jobPost)
    {
        $this->jobPost = $jobPost;
    }

    public function render()
    {
        return view('livewire.application-form');
    }
}
