<?php

namespace App\Filament\Resources\BookResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ChaptersRelationManager extends RelationManager
{
    protected static string $relationship = 'chapters';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->maxLength(255)
                ->columnSpanFull(),
            Forms\Components\TextInput::make('chapter_index')
                ->numeric(),
            Forms\Components\TextInput::make('status')
                ->datalist(['pending_chunk', 'chunked', 'summarized', 'error']),
            Forms\Components\TextInput::make('token_count')
                ->numeric(),
            Forms\Components\KeyValue::make('metadata')
                ->columnSpanFull(),
            Forms\Components\MarkdownEditor::make('summary_md')
                ->columnSpanFull(),
            Forms\Components\Textarea::make('content')
                ->rows(10)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('chapter_index')
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('token_count')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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


