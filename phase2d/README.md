# تحسين رقم العدد: ترقيم تلقائي + تحقّق لطيف

يستبدل ملف EditionForm.php بنسخة:
- رقم العدد يُملأ تلقائيًا (آخر رقم + 1).
- تاريخ العدد يُملأ باليوم تلقائيًا.
- عند تكرار رقم أو تاريخ: تنبيه عربي أنيق أسفل الحقل بدل صفحة الخطأ.

## الخطوات (كل سطر وحده)

### 1) ادخل المجلد
```
cd ~/news-lebanon
```

### 2) انقل nashra-phase2d.zip للمجلد عبر AnyDesk ثم فكّه
```
unzip -o nashra-phase2d.zip -d phase2d
```

### 3) انسخ الملف المحسّن فوق الحالي
```
cp phase2d/app-files/app/Filament/Resources/Editions/Schemas/EditionForm.php src/app/Filament/Resources/Editions/Schemas/EditionForm.php
```

### 4) امسح الذاكرة المؤقتة
```
docker compose exec app php artisan optimize:clear
```

### 5) جرّب في Safari
```
http://localhost:8080/admin
```
افتح «الأعداد» ← «إضافة عدد»: سترى رقم العدد والتاريخ مملوءين تلقائيًا.
جرّب وضع رقم مكرّر واحفظ: يظهر تنبيه عربي أسفل الحقل بدل صفحة خطأ.
