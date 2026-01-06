<?php

namespace App\Filament\Resources\ChapterChunkResource\Pages;

use App\Filament\Resources\ChapterChunkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChapterChunk extends EditRecord
{
    protected static string $resource = ChapterChunkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
