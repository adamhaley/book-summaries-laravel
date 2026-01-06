<?php

namespace App\Filament\Resources\SummaryV2Resource\Pages;

use App\Filament\Resources\SummaryV2Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSummaryV2 extends EditRecord
{
    protected static string $resource = SummaryV2Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
