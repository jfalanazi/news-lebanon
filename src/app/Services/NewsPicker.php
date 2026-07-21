<?php

namespace App\Services;

use App\Models\Edition;
use App\Models\NewsCandidate;

class NewsPicker
{
    /**
     * يملأ العدد بأخبار منتقاة: سحب المصادر ← تنقية بالذكاء ← إضافة بلا تكرار.
     * نفس الحدث من مصدرين = خبر واحد (تشابه العناوين ≥ 72%).
     * يعيد عدد الأخبار المضافة.
     */
    public function fill(Edition $edition, int $take = 7): int
    {
        app(NewsFetcher::class)->fetchAll();

        $batch = NewsCandidate::where('used', false)
            ->where('ai_processed', false)
            ->latest()->take(10)->get();

        if ($batch->isNotEmpty()) {
            app(AiNewsCurator::class)->process($batch);
        }

        $candidates = NewsCandidate::where('used', false)
            ->where('ai_processed', true)
            ->latest()->take($take * 2)->get();

        $existingTitles = $edition->news()->pluck('title')->all();
        $pos   = (int) $edition->news()->max('position');
        $added = 0;

        foreach ($candidates as $c) {
            if ($added >= $take) {
                break;
            }

            // مرشّح مكرر: يُستهلك بلا إضافة حتى لا يعود في الدفعة التالية
            if ($this->isDuplicate($c->title, $existingTitles)) {
                $c->update(['used' => true]);
                continue;
            }

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

            $existingTitles[] = $c->title;
            $c->update(['used' => true]);
            $added++;
        }

        return $added;
    }

    private function isDuplicate(string $title, array $titles): bool
    {
        $a = $this->normalize($title);
        foreach ($titles as $t) {
            similar_text($a, $this->normalize($t), $pct);
            if ($pct >= 72) {
                return true;
            }
        }

        return false;
    }

    private function normalize(string $t): string
    {
        $t = mb_strtolower(trim($t));
        $t = str_replace(['أ', 'إ', 'آ'], 'ا', $t);
        $t = str_replace('ة', 'ه', $t);

        return preg_replace('/\s+/u', ' ', $t) ?? $t;
    }
}
