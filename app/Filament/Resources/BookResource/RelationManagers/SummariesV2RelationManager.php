<?php

namespace App\Filament\Resources\BookResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SummariesV2RelationManager extends RelationManager
{
    protected static string $relationship = 'summariesV2';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('style')
                ->options([
                    'narrative' => 'Narrative',
                    'bullet_points' => 'Bullet points',
                    'workbook' => 'Workbook',
                ])
                ->required(),
            Forms\Components\Select::make('length')
                ->options([
                    'short' => 'Short',
                    'medium' => 'Medium',
                    'long' => 'Long',
                ])
                ->required(),
            Forms\Components\Textarea::make('complete_book_summary')
                ->rows(8)
                ->columnSpanFull(),
            Forms\Components\Textarea::make('summary')
                ->label('Summary JSON')
                ->rows(12)
                ->helperText('Paste JSON here.')
                ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $state)
                ->dehydrateStateUsing(fn ($state) => is_string($state) ? (json_decode($state, true) ?? []) : ($state ?? []))
                ->columnSpanFull(),
            Forms\Components\Textarea::make('formatted_summary')
                ->label('Formatted summary JSON')
                ->rows(12)
                ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $state)
                ->dehydrateStateUsing(fn ($state) => is_string($state) ? (json_decode($state, true) ?? null) : $state)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('style')->badge(),
                Tables\Columns\TextColumn::make('length')->badge(),
                Tables\Columns\TextColumn::make('updated_at')->since()->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}


