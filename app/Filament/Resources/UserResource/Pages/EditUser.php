<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['filament_role'] = $this->record->profile?->role ?? 'user';
        $data['filament_preferences'] = $this->record->profile?->preferences ?? [
            'style' => 'narrative',
            'length' => '5pg',
        ];

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
