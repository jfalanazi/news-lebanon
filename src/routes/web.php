<?php

use App\Models\Edition;
use App\Services\NewsletterRenderer;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

Route::get('/', function () {
    return view('welcome');
});

// معاينة حيّة للنشرة كـ HTML (الشكل النهائي فورًا بدون توليد صورة)
Route::get('/e/{edition}/preview', function (Edition $edition) {
    $edition->load(['news', 'recommendations', 'events']);
    $data = app(NewsletterRenderer::class)->buildData($edition);

    return View::make('newsletter', $data);
})->name('edition.preview');

// الصفحة العامة لكل عدد — يفتحها الباركود، فيها تفاصيل وروابط الأخبار
Route::get('/n/{issue}', function (int $issue) {
    $edition = Edition::where('issue_number', $issue)->firstOrFail();
    $edition->load(['news', 'recommendations', 'events']);

    return View::make('edition-public', ['edition' => $edition]);
})->name('edition.public');
