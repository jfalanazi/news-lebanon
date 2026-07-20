<?php

namespace App\Filament\Resources\Editions\Pages;

use App\Filament\Resources\Editions\EditionResource;
use App\Models\Edition;
use App\Models\NewsCandidate;
use App\Services\AiNewsCurator;
use App\Services\NewsFetcher;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListEditions extends ListRecords
{
    protected static string $resource = EditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // نشرة اليوم: ينشئ عدد اليوم ويعبّئه بالذكاء ويفتحه — بضغطة واحدة
            Action::make('today')
                ->label('نشرة اليوم')
                ->icon('heroicon-o-bolt')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('تجهيز نشرة اليوم')
                ->modalDescription('ينشئ عدد اليوم إن لم يوجد، يعبّئه بأخبار مُنتقاة بالذكاء، ويفتحه للتحرير.')
                ->modalSubmitActionLabel('جهّز وافتح')
                ->action(function () {
                    $edition = Edition::firstOrCreate(
                        ['edition_date' => now()->toDateString()],
                        ['issue_number' => Edition::nextIssueNumber(), 'status' => 'draft'],
                    );

                    try {
                        app(NewsFetcher::class)->fetchAll();

                        $batch = NewsCandidate::where('used', false)
                            ->where('ai_processed', false)
                            ->latest()->take(10)->get();

                        if ($batch->isNotEmpty()) {
                            app(AiNewsCurator::class)->process($batch);
                        }

                        $toAdd = NewsCandidate::where('used', false)
                            ->where('ai_processed', true)
                            ->latest()->take(7)->get();

                        $pos = (int) $edition->news()->max('position');
                        foreach ($toAdd as $c) {
                            $edition->news()->create([
                                'category'     => $c->category,
                                'url'          => $c->url,
                                'source_name'  => $c->source_name,
                                'title'        => $c->title,
                                'excerpt'      => $c->excerpt,
                                'priority'     => $c->priority ?: 'normal',
                                'position'     => ++$pos,
                                'ai_generated' => true,
                            ]);
                            $c->update(['used' => true]);
                        }

                        Notification::make()->title('تم تجهيز نشرة اليوم ✨')->success()->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('أُنشئ العدد، لكن تعذّر التوليد الذكي')
                            ->body($e->getMessage())
                            ->warning()
                            ->send();
                    }

                    $this->redirect(EditionResource::getUrl('edit', ['record' => $edition]));
                }),

            CreateAction::make(),
        ];
    }
}
