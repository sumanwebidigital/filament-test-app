<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditRole extends EditRecord
{
    
    protected static string $resource = RoleResource::class;

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
            ->title('Role updated')
            ->body('The role has been saved successfully.');
    }
}
