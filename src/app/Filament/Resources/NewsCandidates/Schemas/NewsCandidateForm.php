<?php

namespace App\Filament\Resources\NewsCandidates\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class NewsCandidateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('for_date')
                    ->required(),
                TextInput::make('category'),
                TextInput::make('url')
                    ->url(),
                TextInput::make('source_name'),
                TextInput::make('title')
                    ->required(),
                TextInput::make('excerpt'),
                Toggle::make('used')
                    ->required(),
            ]);
    }
}
