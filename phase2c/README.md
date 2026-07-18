# نشرة لبنان — تعريب لوحة Filament (الطريقة أ: شامل ونظيف)

يعرّب أسماء القوائم والحقول وقيم الأولوية/الحالة/النوع، ويحسّن ترتيب الحقول.
اللغة العربية + RTL فعّلناهما مسبقًا عبر APP_LOCALE=ar.

> القاعدة الذهبية: الصق كل سطر وحده. لا تلصق أسطرًا عربية أو تبدأ بـ #.

## الخطوات

### 1) ادخل المجلد
```
cd ~/news-lebanon
```

### 2) فك ضغط الحزمة
```
unzip -o nashra-phase2c.zip
```

### 3) خذ نسخة احتياطية من الملفات الحالية (احتياط سريع)
```
docker compose exec app cp -R app/Filament/Resources app/Filament/Resources_backup
```

### 4) انسخ الملفات المعرّبة فوق الموجودة
```
cp -R nashra-phase2c/app-files/app/Filament/Resources/. src/app/Filament/Resources/
```

### 5) امسح الذاكرة المؤقتة
```
docker compose exec app php artisan optimize:clear
```

### 6) حدّث اللوحة في Safari
```
http://localhost:8080/admin
```

## النتيجة
- القوائم عربية: الأعداد، الأخبار، التوصيات، الفعاليات، المصادر، الإعدادات — ومرتّبة منطقيًا.
- كل الحقول بأسماء عربية.
- الأولوية: عادي/مهم/عاجل بألوان (عاجل أحمر، مهم أصفر).
- الحالة: مسودة/قيد المراجعة/معتمد/منشور بألوان.
- النوع (توصيات): مطعم/معلم/منتزه/مقهى.
- العدد يظهر بتاريخه بدل الرقم الخام في القوائم المرتبطة.

## لو ظهر خطأ
تراجع فورًا للنسخة الاحتياطية:
```
docker compose exec app rm -rf app/Filament/Resources
```
```
docker compose exec app cp -R app/Filament/Resources_backup app/Filament/Resources
```
```
docker compose exec app php artisan optimize:clear
```
ثم الصق لي رسالة الخطأ.

## بعد نجاح التعريب
احذف النسخة الاحتياطية (اختياري):
```
docker compose exec app rm -rf app/Filament/Resources_backup
```
