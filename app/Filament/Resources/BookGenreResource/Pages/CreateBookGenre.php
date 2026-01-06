<?php

namespace App\Filament\Resources\BookGenreResource\Pages;

use App\Filament\Resources\BookGenreResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBookGenre extends CreateRecord
{
    protected static string $resource = BookGenreResource::class;
}
