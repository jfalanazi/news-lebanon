<?php

namespace App\Filament\Resources\NewsCandidates\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class NewsCandidateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('for_date')
                    ->date(),
                TextEntry::make('category')
                    ->placeholder('-'),
                TextEntry::make('url')
                    ->placeholder('-'),
                TextEntry::make('source_name')
                    ->placeholder('-'),
                TextEntry::make('title'),
                TextEntry::make('excerpt')
                    ->placeholder('-'),
                IconEntry::make('used')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
