<?php

namespace App\Filament\Resources\Editions\Pages;

use App\Filament\Resources\Editions\EditionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEdition extends CreateRecord
{
    protected static string $resource = EditionResource::class;

    // بعد الإنشاء نفتح شاشة التحرير مباشرة (المعاينة + التبويبات + التوليد)
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }
}
