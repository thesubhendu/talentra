<?php

namespace App\Filament\Resources\Applications\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;
use Illuminate\Support\Facades\Storage;

class ApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jobPost.title')
                    ->searchable(),
                TextColumn::make('candidate.first_name')
                    ->label('Candidate')
                    ->state(fn ($record) => $record->candidate->first_name.' '.$record->candidate->last_name)
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('match_score')
                    ->label('Match Score')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 1).'%' : 'N/A')
                    ->badge()
                    ->color(fn ($state) => $state ? match (true) {
                        $state >= 80 => 'success',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    } : 'gray')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                MediaAction::make('resume_url')
                    ->label('Resume')
                    ->media(fn ($record) => Storage::url($record->resume_url))
                    ->mediaType('pdf'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
