<?php

namespace App\Filament\Resources\NewsCandidates;

use App\Filament\Resources\NewsCandidates\Pages\CreateNewsCandidate;
use App\Filament\Resources\NewsCandidates\Pages\EditNewsCandidate;
use App\Filament\Resources\NewsCandidates\Pages\ListNewsCandidates;
use App\Filament\Resources\NewsCandidates\Pages\ViewNewsCandidate;
use App\Filament\Resources\NewsCandidates\Schemas\NewsCandidateForm;
use App\Filament\Resources\NewsCandidates\Schemas\NewsCandidateInfolist;
use App\Filament\Resources\NewsCandidates\Tables\NewsCandidatesTable;
use App\Models\NewsCandidate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NewsCandidateResource extends Resource
{
    protected static ?string $model = NewsCandidate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $navigationLabel = 'الأخبار المرشّحة';
    protected static ?string $modelLabel = 'خبر مرشّح';
    protected static ?string $pluralModelLabel = 'الأخبار المرشّحة';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return NewsCandidateForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return NewsCandidateInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NewsCandidatesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNewsCandidates::route('/'),
            'create' => CreateNewsCandidate::route('/create'),
            'view' => ViewNewsCandidate::route('/{record}'),
            'edit' => EditNewsCandidate::route('/{record}/edit'),
        ];
    }
}
