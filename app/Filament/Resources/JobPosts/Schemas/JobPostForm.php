<?php

namespace App\Filament\Resources\JobPosts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class JobPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required(),
                TextInput::make('employment_type')
                    ->required(),
                TextInput::make('salary_min')
                    ->required()
                    ->numeric(),
                TextInput::make('salary_max')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('posted_at')
                    ->required(),
                DateTimePicker::make('expires_at'),
                TextInput::make('location')
                    ->required(),
            ]);
    }
}
