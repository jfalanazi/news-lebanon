# المرحلة 3.3 — ربط القالب ببياناتك الحقيقية

نحوّل القالب الثابت إلى قالب Blade حيّ يُملأ من قاعدة البيانات:
- أخبار/توصيات/فعاليات العدد من الجداول.
- الطقس والصلاة يُجلبان تلقائيًا وقت التوليد (أو من لقطة العدد إن وُجدت).
- أمر artisan لتوليد صورة أي عدد.

> الصق كل سطر وحده. بلا شرطات مائلة.

## الملفات في الحزمة
- resources/views/newsletter.blade.php  (القالب الحيّ)
- resources/views/_nashra_icons.blade.php  (أيقونات SVG مساعدة)
- app/Services/NewsletterRenderer.php  (يجمع البيانات ويولّد الصورة)
- app/Console/Commands/RenderNewsletter.php  (أمر nashra:render)

## الخطوات

### 1) ادخل المجلد
```
cd ~/news-lebanon
```

### 2) انقل nashra-phase3c.zip عبر AnyDesk ثم فكّه
```
unzip -o nashra-phase3c.zip -d phase3c
```

### 3) انسخ الملفات (4 أوامر)
```
cp phase3c/app-files/resources/views/newsletter.blade.php src/resources/views/
```
```
cp phase3c/app-files/resources/views/_nashra_icons.blade.php src/resources/views/
```
```
cp phase3c/app-files/app/Services/NewsletterRenderer.php src/app/Services/NewsletterRenderer.php
```
```
cp phase3c/app-files/app/Console/Commands/RenderNewsletter.php src/app/Console/Commands/RenderNewsletter.php
```
> ملاحظة: مجلدا Services و Console/Commands يُنشآن تلقائيًا مع النسخ. إن شكا الأمر من عدم وجود المجلد، أنشئه:
> mkdir -p src/app/Services  و  mkdir -p src/app/Console/Commands

### 4) امسح الذاكرة
```
docker compose exec app php artisan optimize:clear
```

### 5) جهّز بيانات عدد للتجربة (من اللوحة)
افتح http://localhost:8080/admin ← الأعداد ← افتح عددًا (أو أنشئ عددًا) ←
ثم من «الأخبار» أضف خبرًا أو اثنين واربطهما بهذا العدد (اختر العدد من قائمة العدد)،
وأضف توصية وفعالية إن أردت. احفظ.

### 6) ولّد صورة العدد (استبدل 1 برقم عددك)
```
docker compose exec app php artisan nashra:render 1
```
> أو بلا رقم لأحدث عدد:
```
docker compose exec app php artisan nashra:render
```

### 7) افتح الصورة الناتجة (استبدل 1 برقم عددك)
```
open src/storage/app/newsletters/edition-1.png
```

## النتيجة
الصورة الآن مبنية من بياناتك الحقيقية: الأخبار التي أدخلتها بأولوياتها،
والطقس والصلاة محدّثان تلقائيًا لبيروت، والعدد واليوم والتاريخ من قاعدة البيانات.

## ملاحظات
- إذا كان العدد بلا أخبار، سيظهر بلوك الأخبار فارغًا — أضف أخبارًا من اللوحة.
- الطقس/الصلاة يُجلبان من الإنترنت وقت التوليد؛ إن تعذّر، تظهر قيم افتراضية.
- الأخبار تُرتّب حسب حقل «الترتيب» (position) في اللوحة.

## بعد النجاح
ننتقل لـ3.4: زر «توليد الصورة» داخل لوحة Filament مباشرة (بدل سطر الأوامر).
