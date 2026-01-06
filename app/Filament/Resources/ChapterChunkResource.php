<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChapterChunkResource\Pages;
use App\Models\ChapterChunk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ChapterChunkResource extends Resource
{
    protected static ?string $model = ChapterChunk::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Library';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('chapter_id')
                    ->relationship('chapter', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Textarea::make('content')
                    ->columnSpanFull(),
                Forms\Components\KeyValue::make('metadata')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('chunk_index')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('chapter.book.title')
                    ->label('Book')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('chapter.chapter_index')
                    ->label('Chapter #')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('chapter.title')
                    ->label('Chapter')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('chunk_index')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('content')
                    ->limit(80)
                    ->wrap(),
                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->sortable(),
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
            'index' => Pages\ListChapterChunks::route('/'),
            'create' => Pages\CreateChapterChunk::route('/create'),
            'edit' => Pages\EditChapterChunk::route('/{record}/edit'),
        ];
    }
}
