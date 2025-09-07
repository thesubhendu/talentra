<?php

namespace App\Filament\Resources\Applications\Schemas;

use App\ApplicationStatus;
use App\Models\Application;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Storage;
use Filament\Infolists\Components\TextEntry;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;

class ApplicationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('jobPost.title')->label('Job Post Title'),
                TextEntry::make('candidate.id')->label('Candidate ID'),
                TextEntry::make('candidate.first_name')->label('Candidate First Name'),
                TextEntry::make('candidate.last_name')->label('Candidate Last Name'),
                TextEntry::make('candidate.email')->label('Candidate Email'),
                TextEntry::make('candidate.phone')->label('Candidate Phone'),
                TextEntry::make('jobPost.id')->label('Job Post ID'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            MediaAction::make('resume_url')
                ->label('View Resume')
                ->media(fn($record) => Storage::url($record->resume_url))
                ->mediaType('pdf'),

            TextEntry::make('status'),
            Action::make('change_status')
                ->label('Change Status')
                ->schema([
                    Select::make('status')
                        ->options(ApplicationStatus::class)
                        ->default(fn($record) => $record->status)
                        ->required(),
                ])
                ->action(function ($data, Application $record) {
                    $record->status = $data['status'];
                    $record->save();
                })

            ]);
;
    }
}
