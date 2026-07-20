<?php

namespace App\Services;

use App\Models\Edition;
use App\Models\Setting;
use Carbon\Carbon;

class NewsletterCaption
{
    private array $dow = ['الأحد','الإثنين','الثلاثاء','الأربعاء','الخميس','الجمعة','السبت'];
    private array $gulf = ['يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر'];

    /**
     * يبني نص المشاركة الجاهز للنسخ إلى واتساب.
     */
    public function build(Edition $edition): string
    {
        $edition->loadMissing(['news', 'recommendations', 'events']);

        $date = Carbon::parse($edition->edition_date);
        $day  = $this->dow[$date->dayOfWeek];
        $greg = $date->day . ' ' . $this->gulf[$date->month - 1] . ' ' . $date->year;

        $lines = [];
        $lines[] = "🇱🇧 نشرة لبنان — العدد {$edition->issue_number}";
        $lines[] = "🗓️ {$day} · {$greg}";
        $lines[] = '';
        $lines[] = '📌 أهم الأخبار:';

        $i = 1;
        foreach ($edition->news->take(6) as $n) {
            $flag = match ($n->priority) {
                'breaking', 'عاجل'  => '🔴 ',
                'important', 'مهم' => '🟡 ',
                default             => '',
            };
            $lines[] = "{$i}. {$flag}{$n->title}";
            $i++;
        }

        if ($edition->recommendations->isNotEmpty()) {
            $lines[] = '';
            $lines[] = '⭐ توصية اليوم: ' . $edition->recommendations->first()->name;
        }

        $quote = $edition->quote ?: Setting::get('default_quote', '');
        if ($quote) {
            $lines[] = '';
            $lines[] = "« {$quote} »";
        }

        $link = $edition->caption_link ?: url('/n/' . $edition->issue_number);
        $lines[] = '';
        $lines[] = "🔗 التفاصيل والروابط: {$link}";

        return implode("\n", $lines);
    }
}
