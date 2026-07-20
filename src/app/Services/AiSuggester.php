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
            'max_tokens' => 1000,
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

    /** يلخّص خبرًا إلى نبذة سطر واحد (≤ 20 كلمة) */
    public function summarize(string $title, ?string $body = null): string
    {
        $key = config('services.anthropic.key');
        if (empty($key)) {
            throw new \RuntimeException('لم يُضبط ANTHROPIC_API_KEY في ملف .env على السيرفر.');
        }

        $source = trim($title . "\n" . ($body ?? ''));
        $prompt = "لخّص الخبر التالي في **نبذة عربية فصيحة، سطر واحد، لا تتجاوز 20 كلمة**، دون مقدمات.\n\nالخبر:\n" . $source;

        $resp = Http::withHeaders([
            'x-api-key'         => $key,
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ])->timeout(60)->post('https://api.anthropic.com/v1/messages', [
            'model'      => config('services.anthropic.model'),
            'max_tokens' => 200,
            'messages'   => [['role' => 'user', 'content' => $prompt]],
        ]);

        if (! $resp->successful()) {
            throw new \RuntimeException('فشل الاتصال بالذكاء (' . $resp->status() . '): ' . $resp->body());
        }

        return trim((string) $resp->json('content.0.text', ''));
    }

    /** يقترح فعاليات لبنانية قادمة (تواريخها تقريبية وتحتاج تدقيقًا) */
    public function events(int $count = 3): array
    {
        $today = now()->toDateString();
        $year = now()->year;

        $prompt = "اليوم هو {$today}. اقترح {$count} فعاليات أو مهرجانات لبنانية موسمية أو متكرّرة **قادمة**.\n"
            . "شروط مهمة:\n"
            . "- يجب أن تكون التواريخ **بعد {$today}** وضمن عام {$year} أو ما بعده. لا تستخدم أي تاريخ ماضٍ.\n"
            . "- إن لم تكن متأكدًا من التاريخ الدقيق، اترك start و end فارغَين.\n\n"
            . "لكل فعالية أعِد:\n"
            . "- title: العنوان بالعربية\n"
            . "- category: واحد من [ثقافي, سياحي, فني, رياضي]\n"
            . "- start: تاريخ بداية بصيغة YYYY-MM-DD (لاحق لليوم) أو فارغ\n"
            . "- end: تاريخ نهاية بصيغة YYYY-MM-DD أو فارغ\n"
            . "أعِد JSON فقط (بلا نص آخر): مصفوفة بالحقول title, category, start, end.";

        return $this->call($prompt);
    }
}
