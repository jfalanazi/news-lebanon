<?php

namespace App\Console\Commands;

use App\Models\Edition;
use App\Services\NewsletterRenderer;
use Illuminate\Console\Command;

class RenderNewsletter extends Command
{
    protected $signature = 'nashra:render {issue? : رقم العدد المراد توليده}';
    protected $description = 'يولّد صورة النشرة لعدد معيّن (أو أحدث عدد إن لم يُحدّد)';

    public function handle(NewsletterRenderer $renderer): int
    {
        $issue = $this->argument('issue');

        $edition = $issue
            ? Edition::where('issue_number', $issue)->first()
            : Edition::orderByDesc('edition_date')->first();

        if (! $edition) {
            $this->error('لا يوجد عدد مطابق. أنشئ عددًا في اللوحة أولًا.');
            return self::FAILURE;
        }

        $edition->load(['news', 'recommendations', 'events']);

        $this->info("جارِ توليد العدد رقم {$edition->issue_number}…");
        $path = $renderer->render($edition);
        $this->info('تم الحفظ في: ' . $path);

        return self::SUCCESS;
    }
}
