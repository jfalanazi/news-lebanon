# المرحلة 3.1 — البنية التحتية لتوليد الصور (Node + Chromium + Browsershot)

نضيف لبيئة Docker: Node 22 و Chromium (بمعمارية ARM64) وخطوطًا عربية، ثم مكتبة Browsershot.
مضبوط لجهاز M4. في النهاية نختبر بإنتاج صورة تجريبية.

> القاعدة الذهبية: الصق كل سطر وحده. لا تلصق أسطرًا عربية أو تبدأ بـ #.
> لا تضف شرطات مائلة \ عند اللصق.

## الخطوات

### 1) ادخل المجلد
```
cd ~/news-lebanon
```

### 2) انقل nashra-phase3a.zip للمجلد عبر AnyDesk ثم فكّه
```
unzip -o nashra-phase3a.zip -d phase3a
```

### 3) استبدل ملف Dockerfile بالنسخة الجديدة
```
cp phase3a/docker/php/Dockerfile docker/php/Dockerfile
```

### 4) أعد بناء صورة PHP (تأخذ 3-6 دقائق — تنزّل Node و Chromium)
```
docker compose build app
```

### 5) أعد تشغيل الحاويات بالصورة الجديدة
```
docker compose up -d
```

### 6) تأكّد أن Chromium و Node مثبّتان
```
docker compose exec app chromium --version
```
```
docker compose exec app node -v
```
> يجب أن يظهر إصدار Chromium وإصدار Node (v22).

### 7) ثبّت مكتبة Browsershot
```
docker compose exec app composer require spatie/browsershot
```

### 8) ثبّت Puppeteer داخل المشروع (بلا تنزيل Chromium — يستخدم Chromium النظام)
```
docker compose exec app npm install puppeteer
```

### 9) اختبار: أنتج صورة تجريبية عربية
```
docker compose exec app php -r "require '/var/www/vendor/autoload.php'; \Spatie\Browsershot\Browsershot::html('<div style=\"font-family:sans-serif;font-size:40px;text-align:center;padding:60px;color:#146B3F;\">مرحبًا — نشرة لبنان ١٢٣</div>')->setChromePath('/usr/bin/chromium')->noSandbox()->windowSize(700,300)->save('/var/www/storage/app/test.png'); echo 'OK saved';"
```
> يجب أن يطبع: OK saved

### 10) افتح الصورة التجريبية للتأكد
الصورة الآن في مجلد المشروع على الماك:
```
open src/storage/app/test.png
```
> يجب أن تفتح صورة مكتوب فيها «مرحبًا — نشرة لبنان ١٢٣» بخط عربي أخضر.

## إذا نجح كل شيء
البنية جاهزة! تظهر الصورة التجريبية = Browsershot يعمل ويرسم العربية.
نمرّ للخطوة 3.2: بناء قالب HTML/CSS للنشرة الحقيقية.

## إذا ظهر خطأ
الصق رسالة الخطأ كما هي. الأخطاء المتوقّعة وحلولها:
- "Could not find browser" → Chromium لم يُثبّت؛ راجع الخطوة 6.
- "Failed to launch ... No usable sandbox" → موجود ->noSandbox() في الأمر، تأكد من نسخه كاملًا.
- "npm: not found" أو "node: not found" → راجع الخطوة 6 (إعادة البناء لم تكتمل).
