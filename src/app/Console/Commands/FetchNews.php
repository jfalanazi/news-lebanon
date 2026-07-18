<?php

namespace App\Console\Commands;

use App\Services\NewsFetcher;
use Illuminate\Console\Command;

class FetchNews extends Command
{
    protected $signature = 'nashra:fetch';
    protected $description = 'يسحب أحدث الأخبار من مصادر RSS المفعّلة إلى المجموعة المرشّحة';

    public function handle(NewsFetcher $fetcher): int
    {
        $this->info('جارِ السحب من المصادر…');
        $report = $fetcher->fetchAll();

        if (empty($report)) {
            $this->warn('لا توجد مصادر مفعّلة لها رابط RSS. أضف الروابط من شاشة «المصادر».');
            return self::SUCCESS;
        }

        foreach ($report as $name => $count) {
            $this->line("  {$name}: " . (is_int($count) ? "أُضيف {$count}" : $count));
        }

        return self::SUCCESS;
    }
}
