<?php

namespace App\Filament\Resources\Candidates\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CandidateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                    ->required(),
                TextInput::make('last_name')
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('linkedin_url'),
                TextInput::make('github_url'),
                TextInput::make('portfolio_url'),
            ]);
    }
}
