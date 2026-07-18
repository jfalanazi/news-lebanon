# نشرة لبنان — المرحلة 2: لوحة Filament + الأدوار الثلاثة

نركّب لوحة تحكم أونلاين (Filament v5، المتوافق مع Laravel 13)، ننشئ حساب المدير،
ونولّد شاشات إدارة لكل جداول النشرة. الأدوار: مدير + محرّر + ناشر.

> القاعدة الذهبية (كما في المرحلة 1): لا تلصق أي سطر يبدأ بـ #، ولا الأسطر العربية.
> الصق الأوامر فقط، سطرًا سطرًا، واضغط Enter بعد كل أمر.

---

## أولًا: انسخ ملفات هذه الحزمة إلى المشروع

ضع مجلد `nashra-phase2` بجانب مشروعك، ثم من داخل `~/news-lebanon` نفّذ لاحقًا أوامر النسخ في الخطوة 4.
(أو انسخ `app-files` يدويًا عبر Finder فوق `src`.)

---

## الخطوات (الصق كل سطر وحده)

### 1) ادخل مجلد المشروع
```
cd ~/news-lebanon
```

### 2) ركّب Filament v5 داخل الحاوية
```
docker compose exec app composer require filament/filament:"^5.0"
```

### 3) ثبّت لوحة Filament (تنشئ لوحة admin)
```
docker compose exec app php artisan filament:install --panels
```
> إن سألك عن اسم اللوحة (panel id) اكتب: admin
> هذا الأمر ينشئ ويُسجّل مزوّدًا باسم app/Providers/Filament/AdminPanelProvider.php تلقائيًا.
> لو ظهر خطأ لاحقًا عند فتح /admin، تأكد أن هذا المزوّد مُسجّل في ملف bootstrap/providers.php.

### 4) انسخ ملفات الأدوار وحساب المدير فوق المشروع
انسخ محتوى الحزمة (بعد وضع مجلد nashra-phase2 داخل ~/news-lebanon):
```
cp -R nashra-phase2/app-files/app/Enums/* src/app/Enums/ 2>/dev/null || (mkdir -p src/app/Enums && cp -R nashra-phase2/app-files/app/Enums/* src/app/Enums/)
```
```
cp nashra-phase2/app-files/app/Models/User.php src/app/Models/User.php
```
```
cp nashra-phase2/app-files/database/migrations/* src/database/migrations/
```
```
cp nashra-phase2/app-files/database/seeders/UserSeeder.php src/database/seeders/
```

### 5) نفّذ هجرة عمود الدور
```
docker compose exec app php artisan migrate
```

### 6) أنشئ الحسابات (المدير + محرّر وناشر تجريبيين)
```
docker compose exec app php artisan db:seed --class="Database\Seeders\UserSeeder"
```

### 7) ولّد شاشات إدارة الجداول (موارد Filament)
كل أمر ينشئ شاشة كاملة (قائمة/إضافة/تعديل) للجدول. الصقها واحدًا واحدًا:
```
docker compose exec app php artisan make:filament-resource Edition --generate
```
```
docker compose exec app php artisan make:filament-resource NewsItem --generate
```
```
docker compose exec app php artisan make:filament-resource Recommendation --generate
```
```
docker compose exec app php artisan make:filament-resource Event --generate
```
```
docker compose exec app php artisan make:filament-resource Source --generate
```
```
docker compose exec app php artisan make:filament-resource Setting --generate
```

### 8) امسح الذاكرة المؤقتة
```
docker compose exec app php artisan optimize:clear
```

### 9) افتح اللوحة في Safari
```
http://localhost:8080/admin
```
سجّل الدخول:
- البريد: info@jaber.sa
- كلمة المرور: Change_Me_123

---

## بعد الدخول مباشرة
1. غيّر كلمة مرور المدير (من إدارة المستخدمين لاحقًا، أو نضيف شاشة ملف شخصي في تحسين قادم).
2. احذف الحسابين التجريبيين (editor@nashra.local و publisher@nashra.local) أو غيّر بياناتهما.

---

## ماذا أنجزت هذه المرحلة
- لوحة تحكم أونلاين على `/admin`.
- حساب مدير باسمك، وأساس الأدوار الثلاثة (مدير/محرّر/ناشر).
- شاشات إدارة لكل جداول النشرة (أخبار بأولوياتها، توصيات، فعاليات، مصادر، إعدادات، أعداد).

## تنبيه أمني بسيط
غيّر كلمات المرور التجريبية فورًا. عند الانتقال لـ VPS لاحقًا، نضبط HTTPS ونظّف الحسابات التجريبية.

## المرحلة القادمة (3)
نقل قالب التصميم إلى السيرفر وتوليد صورة النشرة (Browsershot) بضغطة من داخل اللوحة،
مع ضبط صلاحيات الأدوار التفصيلية (المحرّر يجهّز/يعلّم جاهزًا، الناشر يولّد ويحمّل).
