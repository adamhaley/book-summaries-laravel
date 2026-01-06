<?php

namespace App\Filament\Resources\BookResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SummariesRelationManager extends RelationManager
{
    protected static string $relationship = 'summaries';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'email')
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\TextInput::make('style')
                ->datalist(['narrative', 'bullet_points', 'workbook'])
                ->required(),
            Forms\Components\TextInput::make('length')
                ->datalist(['short', 'medium', 'long', '5pg'])
                ->required(),
            Forms\Components\TextInput::make('file_path')
                ->required()
                ->columnSpanFull(),
            Forms\Components\TextInput::make('tokens_spent')
                ->numeric(),
            Forms\Components\TextInput::make('generation_time')
                ->numeric(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.email')
                    ->label('User')
                    ->searchable(),
                Tables\Columns\TextColumn::make('style')
                    ->badge(),
                Tables\Columns\TextColumn::make('length')
                    ->badge(),
                Tables\Columns\TextColumn::make('tokens_spent')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->sortable(),
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


