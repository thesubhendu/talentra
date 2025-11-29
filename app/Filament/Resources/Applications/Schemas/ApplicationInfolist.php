<?php

namespace App\Filament\Resources\Applications\Schemas;

use App\ApplicationStatus;
use App\Models\Application;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;
use Illuminate\Support\Facades\Storage;

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
                    ->media(fn ($record) => Storage::url($record->resume_url))
                    ->mediaType('pdf'),

                TextEntry::make('status'),
                TextEntry::make('match_score')
                    ->label('Match Score')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 2).'%' : 'Not calculated yet')
                    ->color(fn ($state) => $state ? match (true) {
                        $state >= 80 => 'success',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    } : 'gray')
                    ->icon(fn ($state) => $state ? ($state >= 80 ? 'heroicon-o-check-circle' : ($state >= 60 ? 'heroicon-o-exclamation-circle' : 'heroicon-o-x-circle')) : null),

                Section::make('AI Screening Report')
                    ->visible(fn ($record) => ! empty($record->screening_report))
                    ->schema([
                        TextEntry::make('screening_report_summary')
                            ->label('Summary of Experience')
                            ->state(fn ($record) => $record->screening_report['summary'] ?? '')
                            ->columnSpanFull()
                            ->html(),
                        TextEntry::make('screening_report_strengths')
                            ->label('Strengths')
                            ->state(function ($record) {
                                $strengths = $record->screening_report['strengths'] ?? [];
                                if (empty($strengths) || ! is_array($strengths)) {
                                    return 'No strengths listed';
                                }

                                return '<ul class="list-disc list-inside space-y-1">'.implode('', array_map(fn ($item) => '<li>'.e($item).'</li>', $strengths)).'</ul>';
                            })
                            ->columnSpanFull()
                            ->html(),
                        TextEntry::make('screening_report_weaknesses')
                            ->label('Weaknesses')
                            ->state(function ($record) {
                                $weaknesses = $record->screening_report['weaknesses'] ?? [];
                                if (empty($weaknesses) || ! is_array($weaknesses)) {
                                    return 'No weaknesses listed';
                                }

                                return '<ul class="list-disc list-inside space-y-1">'.implode('', array_map(fn ($item) => '<li>'.e($item).'</li>', $weaknesses)).'</ul>';
                            })
                            ->columnSpanFull()
                            ->html(),
                        TextEntry::make('screening_report_questions')
                            ->label('Suggested Interview Questions')
                            ->state(function ($record) {
                                $questions = $record->screening_report['suggested_questions'] ?? [];
                                if (empty($questions) || ! is_array($questions)) {
                                    return 'No questions suggested';
                                }

                                return '<ol class="list-decimal list-inside space-y-1">'.implode('', array_map(fn ($item) => '<li>'.e($item).'</li>', $questions)).'</ol>';
                            })
                            ->columnSpanFull()
                            ->html(),
                    ])
                    ->collapsible(),

                Action::make('change_status')
                    ->label('Change Status')
                    ->schema([
                        Select::make('status')
                            ->options(ApplicationStatus::class)
                            ->default(fn ($record) => $record->status)
                            ->required(),
                    ])
                    ->action(function ($data, Application $record) {
                        $record->status = $data['status'];
                        $record->save();
                    }),

            ]);
    }
}
