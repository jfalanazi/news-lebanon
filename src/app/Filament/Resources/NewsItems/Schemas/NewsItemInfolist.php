<?php

namespace App\Filament\Resources\NewsItems\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class NewsItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('edition.id')
                    ->label('Edition'),
                TextEntry::make('category')
                    ->placeholder('-'),
                TextEntry::make('url')
                    ->placeholder('-'),
                TextEntry::make('source_name')
                    ->placeholder('-'),
                TextEntry::make('title'),
                TextEntry::make('excerpt')
                    ->placeholder('-'),
                TextEntry::make('priority')
                    ->badge(),
                TextEntry::make('position')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
