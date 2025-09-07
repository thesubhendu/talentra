<?php

namespace App\Filament\Resources\Applications\Schemas;

use App\ApplicationStatus;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class ApplicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('job_post_id')
                    ->relationship('jobPost', 'title')
                    ->required(),
                Select::make('candidate_id')
                    ->relationship('candidate', 'id')
                    ->required(),
                Select::make('status')
                    ->options(ApplicationStatus::class)
                    ->required(),
                Textarea::make('cover_letter')
                    ->columnSpanFull(),
                TextInput::make('resume_url'),
            ]);
    }
}
