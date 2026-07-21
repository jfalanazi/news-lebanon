<?php

namespace App\Support;

/** يحوّل أخطاء Claude API الخام إلى رسائل عربية مفهومة للمحرّر */
class AiError
{
    public static function humanize(int $status, string $body): string
    {
        $msg = (string) (json_decode($body, true)['error']['message'] ?? '');

        if (str_contains($msg, 'credit balance')) {
            return 'رصيد Claude API نفد — اشحن الرصيد من console.anthropic.com ← Plans & Billing ثم أعد المحاولة.';
        }
        if ($status === 401) {
            return 'مفتاح Claude API غير صالح — تحقق من ANTHROPIC_API_KEY في ملف .env على السيرفر.';
        }
        if ($status === 429) {
            return 'ضغط طلبات على Claude API — انتظر دقيقة ثم أعد المحاولة.';
        }
        if ($status >= 500) {
            return 'خدمة Claude مشغولة مؤقتًا — أعد المحاولة بعد قليل.';
        }

        return 'فشل الاتصال بالذكاء (' . $status . ')' . ($msg !== '' ? ': ' . $msg : '.');
    }
}
