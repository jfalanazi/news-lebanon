<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AiSuggester
{
    private function call(string $prompt): array
    {
        $key = config('services.anthropic.key');
        if (empty($key)) {
            throw new \RuntimeException('لم يُضبط ANTHROPIC_API_KEY في ملف .env على السيرفر.');
        }

        $resp = Http::withHeaders([
            'x-api-key'         => $key,
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ])->timeout(90)->post('https://api.anthropic.com/v1/messages', [
            'model'      => config('services.anthropic.model'),
            'max_tokens' => 1500,
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

        return json_decode($m[0], true) ?: [];
    }

    /** يقترح أماكن لبنانية للتوصيات */
    public function recommendations(int $count = 3): array
    {
        $prompt = "اقترح {$count} أماكن لبنانية مميّزة ومعروفة لقسم توصيات في نشرة يومية.\n"
            . "لكل مكان أعِد:\n"
            . "- type: واحد فقط من [restaurant, landmark, park, cafe]\n"
            . "- name: الاسم بالعربية\n"
            . "- area: المنطقة/المدينة\n"
            . "- description: وصف قصير في جملة واحدة\n"
            . "نوّع الأنواع قدر الإمكان. أعِد JSON فقط (بلا نص آخر): مصفوفة بالحقول type, name, area, description.";

        return $this->call($prompt);
    }

    /** يقترح فعاليات لبنانية (تواريخها تقريبية وتحتاج تدقيقًا) */
    public function events(int $count = 3): array
    {
        $prompt = "اقترح {$count} فعاليات أو مهرجانات لبنانية معروفة أو موسمية لقسم فعاليات في نشرة.\n"
            . "لكل فعالية أعِد:\n"
            . "- title: العنوان بالعربية\n"
            . "- category: واحد من [ثقافي, سياحي, فني, رياضي]\n"
            . "- start: تاريخ بداية تقريبي بصيغة YYYY-MM-DD أو اتركه فارغًا إن لم تكن متأكدًا\n"
            . "- end: تاريخ نهاية بصيغة YYYY-MM-DD أو فارغ\n"
            . "أعِد JSON فقط (بلا نص آخر): مصفوفة بالحقول title, category, start, end.";

        return $this->call($prompt);
    }
}
