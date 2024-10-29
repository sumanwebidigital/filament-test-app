<?php

namespace App\Filament\Resources\LocationResource\Pages;

use App\Filament\Resources\LocationResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditLocation extends EditRecord
{
    protected static string $resource = LocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // protected function getSavedNotificationTitle(): ?string
    // {
    //     return 'Role updated';
    // }

    protected function getSavedNotification(): ?Notification 
    {
        return Notification::make()
            ->success()
            ->title('Location updated')
            ->body('The location has been saved successfully.');
    }
}
