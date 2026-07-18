<?php

namespace App\Filament\Resources\NewsCandidates\Pages;

use App\Filament\Resources\NewsCandidates\NewsCandidateResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditNewsCandidate extends EditRecord
{
    protected static string $resource = NewsCandidateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
