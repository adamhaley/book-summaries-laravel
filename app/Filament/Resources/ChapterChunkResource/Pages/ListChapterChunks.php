<?php

namespace App\Filament\Resources\ChapterChunkResource\Pages;

use App\Filament\Resources\ChapterChunkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChapterChunks extends ListRecords
{
    protected static string $resource = ChapterChunkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
