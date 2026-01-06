<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers\ChaptersRelationManager;
use App\Filament\Resources\BookResource\RelationManagers\SummariesRelationManager;
use App\Filament\Resources\BookResource\RelationManagers\SummariesV2RelationManager;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Library';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Book')
                    ->columns(12)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(8),
                        Forms\Components\TextInput::make('author')
                            ->maxLength(255)
                            ->columnSpan(4),
                        Forms\Components\Select::make('book_genre_id')
                            ->label('Genre')
                            ->relationship('genre', 'name')
                            ->searchable()
                            ->preload()
                            ->columnSpan(4),
                        Forms\Components\TextInput::make('status')
                            ->datalist(['pending_ingest', 'ingested', 'error'])
                            ->helperText('Free-text status, with suggestions.')
                            ->columnSpan(4),
                        Forms\Components\Toggle::make('live')
                            ->inline(false)
                            ->columnSpan(4),
                        Forms\Components\TextInput::make('isbn')
                            ->maxLength(255)
                            ->columnSpan(4),
                        Forms\Components\TextInput::make('publication_year')
                            ->numeric()
                            ->minValue(0)
                            ->columnSpan(4),
                        Forms\Components\TextInput::make('page_count')
                            ->numeric()
                            ->minValue(0)
                            ->columnSpan(4),
                        Forms\Components\TextInput::make('cover_image_url')
                            ->label('Cover image URL')
                            ->url()
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('default_summary_pdf_url')
                            ->label('Default summary PDF URL')
                            ->url()
                            ->columnSpan(6),
                    ]),

                Forms\Components\Section::make('Summary')
                    ->schema([
                        Forms\Components\MarkdownEditor::make('summary')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('author')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('genre.name')
                    ->label('Genre')
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('live')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('chapters_count')
                    ->counts('chapters')
                    ->label('Chapters')
                    ->sortable(),
                Tables\Columns\TextColumn::make('summaries_count')
                    ->counts('summaries')
                    ->label('Summaries')
                    ->sortable()
                    ->toggleable(),
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
                Tables\Filters\SelectFilter::make('book_genre_id')
                    ->label('Genre')
                    ->relationship('genre', 'name'),
                Tables\Filters\TernaryFilter::make('live'),
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
            ChaptersRelationManager::class,
            SummariesRelationManager::class,
            SummariesV2RelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
