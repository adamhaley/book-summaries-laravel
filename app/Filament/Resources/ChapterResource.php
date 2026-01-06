<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChapterResource\Pages;
use App\Models\Chapter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ChapterResource extends Resource
{
    protected static ?string $model = Chapter::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Library';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('book_id')
                    ->relationship('book', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('chapter_index')
                    ->numeric()
                    ->helperText('Optional ordering within the book.'),
                Forms\Components\TextInput::make('token_count')
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->datalist(['pending_chunk', 'chunked', 'summarized', 'error'])
                    ->helperText('Free-text status, with suggestions.'),
                Forms\Components\KeyValue::make('metadata')
                    ->columnSpanFull(),
                Forms\Components\MarkdownEditor::make('summary_md')
                    ->label('Summary (Markdown)')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('content')
                    ->rows(12)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('book.title'),
                Tables\Columns\TextColumn::make('chapter_index')
                    ->label('#')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('token_count')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListChapters::route('/'),
            'create' => Pages\CreateChapter::route('/create'),
            'edit' => Pages\EditChapter::route('/{record}/edit'),
        ];
    }
}
