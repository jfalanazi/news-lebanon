<?php
// سكربت اختبار: يحوّل قالب النشرة (الجزء الأول) إلى صورة PNG
require '/var/www/vendor/autoload.php';
use Spatie\Browsershot\Browsershot;

Browsershot::html(file_get_contents('/var/www/newsletter_preview.html'))
    ->setChromePath('/usr/bin/chromium')
    ->noSandbox()
    ->windowSize(1080, 100)
    ->fullPage()
    ->waitUntilNetworkIdle()
    ->deviceScaleFactor(2)
    ->save('/var/www/storage/app/newsletter_preview.png');

echo "OK saved newsletter_preview.png\n";
