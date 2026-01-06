<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SummaryResource\Pages;
use App\Models\Summary;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SummaryResource extends Resource
{
    protected static ?string $model = Summary::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-down';

    protected static ?string $navigationGroup = 'Summaries';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'email')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('book_id')
                    ->relationship('book', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('style')
                    ->required()
                    ->datalist(['narrative', 'bullet_points', 'workbook'])
                    ->maxLength(255),
                Forms\Components\TextInput::make('length')
                    ->required()
                    ->datalist(['short', 'medium', 'long', '5pg'])
                    ->maxLength(255),
                Forms\Components\TextInput::make('file_path')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('tokens_spent')
                    ->numeric(),
                Forms\Components\TextInput::make('generation_time')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.email')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('book.title'),
                Tables\Columns\TextColumn::make('style')
                    ->searchable(),
                Tables\Columns\TextColumn::make('length')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tokens_spent')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('generation_time')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('file_path')
                    ->label('File')
                    ->limit(50)
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->sortable()
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
            'index' => Pages\ListSummaries::route('/'),
            'create' => Pages\CreateSummary::route('/create'),
            'edit' => Pages\EditSummary::route('/{record}/edit'),
        ];
    }
}
