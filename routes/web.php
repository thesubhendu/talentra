<?php

use App\Livewire\ApplicationForm;
use App\Models\JobPost;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/careers', function () {

    $jobPosts = JobPost::all();
    return view('careers', compact('jobPosts'));
});

Route::get('apply/{jobPost}', ApplicationForm::class)->name('apply');