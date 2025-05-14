<?php

namespace App\Filament\Resources\PaymentArchivedResource\Pages;

use App\Filament\Resources\PaymentArchivedResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaymentArchiveds extends ListRecords
{
    protected static string $resource = PaymentArchivedResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
