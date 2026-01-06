<?php

namespace App\Filament\Resources\BookGenreResource\Pages;

use App\Filament\Resources\BookGenreResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookGenres extends ListRecords
{
    protected static string $resource = BookGenreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
