# CONTEXT — ابدأ هنا (لأي محادثة/نموذج كلود جديد)

> اقرأ هذا الملف أولًا لتفهم المشروع وتكمل العمل فورًا. الكود كله في هذا المجلد ومزامن مع GitHub.

## ما هو المشروع
«نشرة لبنان» — نظام لإنتاج نشرة إخبارية يومية عن لبنان. ثلاث مخرجات:
1. **صورة بوستر PNG** تُشارك في واتساب (تُولّد من قالب HTML عبر Browsershot/Chromium، عرض 1080px).
2. **صفحة ويب عامة لكل عدد** (`/n/{رقم}`) فيها الأخبار وروابطها — يفتحها الباركود في البوستر.
3. **لوحة تحكم إدارية** (Filament v4، عربية RTL) لتجهيز العدد.

## التقنية
Laravel 13 · Filament v4 · MySQL · Docker · Browsershot (توليد الصورة) · Claude Haiku API (تنقية الأخبار) · نشر عبر Cloudflare Tunnel.

## الروابط
- الموقع: `https://nashra.ijaber.com/admin` · صفحة عدد: `https://nashra.ijaber.com/n/12`
- GitHub: `github.com/jfalanazi/news-lebanon` (فرع `main`)

## البنية (أهم المسارات داخل `src/`)
- `app/Filament/Resources/Editions/` — الأعداد (النموذج، الجدول، الصفحات، وRelationManagers للأخبار/التوصيات/الفعاليات).
- `app/Filament/Resources/{Sources,Users,Settings,NewsCandidates}/` — المصادر، الأعضاء، الإعدادات، المرشّحة (المرشّحة مخفية من القائمة).
- `app/Services/` — `NewsletterRenderer` (توليد الصورة)، `NewsletterCaption` (تعليق واتساب)، `NewsFetcher` (سحب RSS)، `AiNewsCurator` + `AiSuggester` (الذكاء).
- `resources/views/newsletter.blade.php` — قالب البوستر (تصميم تحريري أنيق).
- `resources/views/edition-public.blade.php` — صفحة العدد العامة.
- `resources/views/filament/edition-preview.blade.php` — المعاينة الحيّة داخل اللوحة.
- `app/Models/` — Edition, NewsItem, Recommendation, Event, Source, Setting, NewsCandidate, User.

## طريقة العمل (مهم)
1. عدّل الملفات في هذا المجلد.
2. `git commit` ثم `git push origin main`.
3. **النشر تلقائي:** الماك ميني (السيرفر) يسحب من GitHub كل دقيقتين عبر cron (`autodeploy.sh`) ويطبّق على `nashra.ijaber.com`. لا تحتاج تدخّلًا يدويًا على السيرفر لتغييرات الكود.
4. الأسرار (مفتاح Claude، رمز Cloudflare) في ملفات `.env` **على الماك ميني فقط** (غير مرفوعة). تفاصيلها في `OPERATIONS.md`.

## ملفات مرجعية
- `OPERATIONS.md` — التشغيل والصيانة وتدوير الأسرار وأماكن ملفات `.env`.
- `DEPLOY.md` — إعداد السيرفر ونفق Cloudflare من الصفر.
- `ROADMAP.md` — تقرير التحسينات المقترحة.

## الحالة الحالية (منجز)
لوحة عربية بهوية خضراء أرزية · إدارة الأخبار/التوصيات/الفعاليات داخل العدد · توليد ذكي للأخبار (Claude) · معاينة حيّة (صورة ↔ صفحة ويب) · زر أساسي واحد متدرّج (توليد ذكي ← الصورة ← نشر) + مؤشّر خطوات · صفحة عامة لكل عدد + Open Graph + مشاركة واتساب · باركود تلقائي · اختيار المدينة (يضبط الطقس/الصلاة) · قسم الأعضاء بالأدوار · نشر تلقائي شغّال.

## كيف تكمل
اسأل المستخدم عن المطلوب، نفّذه في الكود، ثم commit + push. راجع `git log --oneline -20` لتشوف آخر ما تم.
