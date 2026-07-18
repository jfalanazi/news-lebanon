<?php
namespace App\Filament\Resources\NewsCandidates\Tables;

use App\Models\Edition;
use App\Models\NewsItem;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class NewsCandidatesTable
{
    private static function addToLatestEdition(Collection $records): int
    {
        $edition = Edition::orderByDesc('edition_date')->first();
        if (! $edition) {
            return -1;
        }
        $pos = (int) NewsItem::where('edition_id', $edition->id)->max('position');
        $added = 0;
        foreach ($records as $c) {
            if ($c->used) {
                continue;
            }
            NewsItem::create([
                'edition_id'  => $edition->id,
                'category'    => $c->category,
                'url'         => $c->url,
                'source_name' => $c->source_name,
                'title'       => $c->title,
                'excerpt'     => $c->excerpt,
                'priority'    => 'normal',
                'position'    => ++$pos,
            ]);
            $c->update(['used' => true]);
            $added++;
        }
        return $added;
    }

    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')->label('العنوان')->wrap()->searchable(),
                TextColumn::make('source_name')->label('المصدر')->searchable(),
                TextColumn::make('for_date')->label('التاريخ')->date()->sortable(),
                IconColumn::make('used')->label('استُخدم')->boolean(),
            ])
            ->filters([])
            ->recordActions([
                Action::make('add')
                    ->label('إضافة إلى أحدث عدد')
                    ->icon('heroicon-o-plus')
                    ->color('success')
                    ->visible(fn ($record) => ! $record->used)
                    ->action(function ($record) {
                        $n = self::addToLatestEdition(new Collection([$record]));
                        Notification::make()
                            ->title($n > 0 ? 'أُضيف الخبر إلى أحدث عدد' : 'لا يوجد عدد — أنشئ عددًا أولًا')
                            ->{$n > 0 ? 'success' : 'warning'}()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('addBulk')
                        ->label('إضافة المحدد إلى أحدث عدد')
                        ->icon('heroicon-o-plus')
                        ->color('success')
                        ->action(function (Collection $records) {
                            $n = self::addToLatestEdition($records);
                            Notification::make()
                                ->title($n >= 0 ? "أُضيف {$n} خبرًا إلى أحدث عدد" : 'لا يوجد عدد — أنشئ عددًا أولًا')
                                ->{$n >= 0 ? 'success' : 'warning'}()
                                ->send();
                        }),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
