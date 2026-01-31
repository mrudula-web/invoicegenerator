<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Models\Settings;

class ListSettings extends ListRecords
{
    protected static string $resource = SettingsResource::class;

    protected function getHeaderActions(): array
    {
        // Show Create button only if no record exists
        if (Settings::count() === 0) {
            return [
                \Filament\Actions\CreateAction::make(),
            ];
        }

        return []; // Hide Create button when a record exists
    }
}
