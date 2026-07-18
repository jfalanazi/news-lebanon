<?php

namespace App\Filament\Resources\NewsCandidates\Pages;

use App\Filament\Resources\NewsCandidates\NewsCandidateResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewNewsCandidate extends ViewRecord
{
    protected static string $resource = NewsCandidateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
