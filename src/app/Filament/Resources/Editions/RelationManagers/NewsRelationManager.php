<?php

namespace App\Filament\Resources\Editions\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NewsRelationManager extends RelationManager
{
    protected static string $relationship = 'news';

    protected static ?string $title = 'الأخبار';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')
                ->label('العنوان')
                ->required()
                ->columnSpanFull(),
            Textarea::make('excerpt')
                ->label('النبذة')
                ->rows(2)
                ->maxLength(500)
                ->columnSpanFull(),
            Select::make('priority')
                ->label('الأولوية')
                ->options(['normal' => 'عادي', 'important' => 'مهم', 'breaking' => 'عاجل'])
                ->default('normal')
                ->required(),
            TextInput::make('url')
                ->label('رابط الخبر (اختياري)')
                ->url()
                ->helperText('يُشتق منه اسم المصدر تلقائيًا')
                ->columnSpanFull(),
            TextInput::make('category')
                ->label('التصنيف (اختياري)'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('position')
            ->defaultSort('position')
            ->columns([
                TextColumn::make('title')
                    ->label('العنوان')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('priority')
                    ->label('الأولوية')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'breaking' => 'عاجل', 'important' => 'مهم', default => 'عادي',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'breaking' => 'danger', 'important' => 'warning', default => 'gray',
                    }),
                TextColumn::make('source_name')
                    ->label('المصدر'),
            ])
            ->headerActions([
                CreateAction::make()->label('إضافة خبر'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->emptyStateHeading('لا توجد أخبار بعد')
            ->emptyStateDescription('اضغط «إضافة خبر» لإضافة أول خبر لهذا العدد.');
    }
}
