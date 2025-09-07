<?php

namespace App\Filament\Resources\Applications\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

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
                TextInput::make('status')
                    ->required(),
                Textarea::make('cover_letter')
                    ->columnSpanFull(),
                TextInput::make('resume_url'),
            ]);
    }
}
