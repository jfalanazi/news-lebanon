<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class AiNewsCurator
{
    /**
     * ينظّف مجموعة أخبار مرشّحة عبر Claude:
     * عنوان مختصر + نبذة سطر + تصنيف + أولوية. يحدّث السجلات ويعيد عددها.
     */
    public function process(Collection $candidates): int
    {
        $key = config('services.anthropic.key');
        if (empty($key)) {
            throw new \RuntimeException('لم يُضبط ANTHROPIC_API_KEY في ملف .env على السيرفر.');
        }
        if ($candidates->isEmpty()) {
            return 0;
        }

        $items = $candidates->values()->map(fn ($c) => [
            'id'      => $c->id,
            'title'   => $c->title,
            'excerpt' => $c->excerpt,
            'source'  => $c->source_name,
        ])->all();

        $prompt = "أنت محرّر نشرة إخبارية لبنانية يومية. نظّف الأخبار الخام التالية للنشر:\n"
            . "لكل خبر أعِد:\n"
            . "- title: عنوان مختصر واضح بالعربية الفصحى (بلا اسم المصدر ولا رموز)\n"
            . "- excerpt: نبذة في سطر واحد لا تتجاوز 20 كلمة\n"
            . "- category: واحدة فقط من [سياسة، اقتصاد، أمن، مجتمع، رياضة، ثقافة، دولي]\n"
            . "- priority: breaking للعاجل المهم جدًا، important للمهم، normal للعادي\n\n"
            . "أعِد JSON فقط (بلا أي نص آخر): مصفوفة عناصر بالحقول id, title, excerpt, category, priority.\n\n"
            . "الأخبار الخام:\n" . json_encode($items, JSON_UNESCAPED_UNICODE);

        $resp = Http::withHeaders([
            'x-api-key'         => $key,
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ])->timeout(90)->post('https://api.anthropic.com/v1/messages', [
            'model'      => config('services.anthropic.model'),
            'max_tokens' => 3000,
            'messages'   => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        if (! $resp->successful()) {
            throw new \RuntimeException('فشل الاتصال بالذكاء (' . $resp->status() . '): ' . $resp->body());
        }

        $text = (string) $resp->json('content.0.text', '');
        if (! preg_match('/\[.*\]/s', $text, $m)) {
            throw new \RuntimeException('ردّ الذكاء غير متوقّع — أعد المحاولة.');
        }
        $parsed = json_decode($m[0], true) ?: [];

        $updated = 0;
        foreach ($parsed as $row) {
            $c = $candidates->firstWhere('id', $row['id'] ?? null);
            if (! $c) {
                continue;
            }
            $priority = in_array($row['priority'] ?? '', ['breaking', 'important', 'normal'], true)
                ? $row['priority'] : 'normal';

            $c->update([
                'title'        => trim($row['title'] ?? $c->title) ?: $c->title,
                'excerpt'      => $row['excerpt'] ?? $c->excerpt,
                'category'     => $row['category'] ?? $c->category,
                'priority'     => $priority,
                'ai_processed' => true,
            ]);
            $updated++;
        }

        return $updated;
    }
}
