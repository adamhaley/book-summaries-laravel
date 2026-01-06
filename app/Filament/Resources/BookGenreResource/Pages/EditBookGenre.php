<?php

namespace App\Filament\Resources\BookGenreResource\Pages;

use App\Filament\Resources\BookGenreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookGenre extends EditRecord
{
    protected static string $resource = BookGenreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
