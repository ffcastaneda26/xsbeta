<?php

namespace App\Filament\Company\Resources\AccountingExerciseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PeriodsRelationManager extends RelationManager
{
    protected static string $relationship = 'periods';


    protected static ?string $recordTitleAttribute = 'month';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Periods');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('month')
            ->columns([
                Tables\Columns\TextColumn::make('month')
                    ->label(__('Month'))
                    ->sortable()
                    ->formatStateUsing(fn($state) => date('F', mktime(0, 0, 0, $state, 1))),
                Tables\Columns\TextColumn::make('month')
                    ->label(__('Month'))
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        if (app()->getLocale() === 'en') {
                            return date('F', mktime(0, 0, 0, $state, 1));
                        }
                        return __([
                            1 => 'January',
                            2 => 'February',
                            3 => 'March',
                            4 => 'April',
                            5 => 'May',
                            6 => 'June',
                            7 => 'July',
                            8 => 'August',
                            9 => 'September',
                            10 => 'October',
                            11 => 'November',
                            12 => 'December',
                        ][$state]);
                    }),
                Tables\Columns\IconColumn::make('active')
                    ->label(__('Active'))
                    ->boolean()
                    ->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('toggle_active')
                    ->label(fn($record) => __('Activate'))
                    ->action(function ($record) {
                        $record->update(['active' => !$record->active]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading(fn($record) => __('Activate Period'))
                    ->modalDescription(fn($record) => __('Are you sure you want to activate this period? This will deactivate all other periods for this exercise.'))
                    ->modalSubmitActionLabel(__('Activate'))
                    ->visible(fn($record) => !$record->active)
                    ->button()
                    ->color(color: 'danger'),
            ]);
    }
}
