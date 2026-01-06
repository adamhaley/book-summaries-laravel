<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SummaryV2Resource\Pages;
use App\Models\SummaryV2;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SummaryV2Resource extends Resource
{
    protected static ?string $model = SummaryV2::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Summaries';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('book_id')
                    ->relationship('book', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('length')
                    ->options([
                        'short' => 'Short',
                        'medium' => 'Medium',
                        'long' => 'Long',
                    ])
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('style')
                    ->options([
                        'narrative' => 'Narrative',
                        'bullet_points' => 'Bullet points',
                        'workbook' => 'Workbook',
                    ])
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('complete_book_summary')
                    ->rows(10)
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('book.title'),
                Tables\Columns\TextColumn::make('length')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('style')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSummaryV2S::route('/'),
            'create' => Pages\CreateSummaryV2::route('/create'),
            'edit' => Pages\EditSummaryV2::route('/{record}/edit'),
        ];
    }
}
