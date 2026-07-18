<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// أتمتة سحب الأخبار من مصادر RSS: يوميًا 6 صباحًا بتوقيت بيروت
Schedule::command('nashra:fetch')
    ->dailyAt('06:00')
    ->timezone('Asia/Beirut')
    ->withoutOverlapping();
