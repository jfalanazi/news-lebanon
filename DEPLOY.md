# دليل النشر — نشرة لبنان

## المعمارية
- **الماك بوك (جهازك):** التحرير + الدردشة مع المساعد + `git push`.
- **GitHub (`jfalanazi/news-lebanon`):** المصدر الموحّد وخط النشر.
- **الماك البعيد (Mac mini):** السيرفر — يسحب من GitHub ويشغّل Docker.
- **Cloudflare Tunnel:** رابط HTTPS عام ثابت للنشر أونلاين (مطلوب لأن روابط QR/واتساب تُشارك خارجيًا).

```
الماك بوك  ──push──►  GitHub  ──pull (deploy.sh)──►  الماك البعيد (Docker)  ──►  Cloudflare Tunnel  ──►  الجمهور
```

---

## أولًا: تهيئة السيرفر (الماك البعيد) — مرة واحدة

1. **ثبّت Docker Desktop** على الماك البعيد وشغّله.
2. **استنسخ المستودع:**
   ```
   git clone https://github.com/jfalanazi/news-lebanon.git
   cd news-lebanon
   ```
   (سيطلب مصادقة: استخدم توكن GitHub أو `gh auth login`.)
3. **جهّز `.env`:** انسخ الموجود في `src/.env`، وتأكد من:
   - `APP_ENV=production` و`APP_DEBUG=false`
   - `APP_URL=https://<نطاقك-في-Cloudflare>`
   - `DB_HOST=mysql` `DB_DATABASE=nashra` (كما هو)
4. **أول تشغيل:**
   ```
   docker compose up -d --build
   docker compose exec app php artisan migrate --force
   docker compose exec app php artisan storage:link
   docker compose exec app php artisan optimize:clear
   docker compose exec app php artisan make:filament-user   # أنشئ مستخدم اللوحة
   ```

---

## ثانيًا: دورة العمل اليومية

على الماك بوك (معي): نحرّر ونرفع تلقائيًا لـ GitHub.
على الماك البعيد، للنشر بأمر واحد:

```
./deploy.sh
```

يسحب آخر نسخة، يعيد البناء، ويهيّئ التطبيق. (لأتمتته لاحقًا: cron يشغّل `deploy.sh` كل ساعة، أو GitHub webhook.)

---

## ثالثًا: النشر أونلاين (Cloudflare Tunnel)

1. أنشئ حساب **Cloudflare Zero Trust** (مجاني) واربط نطاقًا.
2. من **Networks → Tunnels** أنشئ نفقًا، وانسخ الـ **Token**.
3. أضف السطر في `.env` بجذر المشروع:
   ```
   CLOUDFLARE_TUNNEL_TOKEN=<التوكن>
   ```
4. في إعداد النفق: **Public Hostname** → `nashra.<نطاقك>` → Service = `http://nginx:80`.
5. شغّل الطبقة العامة:
   ```
   docker compose -f docker-compose.yml -f docker-compose.online.yml up -d
   ```

النتيجة: اللوحة على `https://nashra.<نطاقك>/admin`، وروابط QR/الصور تعمل للجمهور.

> **للتجربة السريعة بلا نطاق:** يمكن استخدام نفق مؤقت (`cloudflared tunnel --url http://localhost:8080`) يعطيك رابط `*.trycloudflare.com` مؤقت.

---

## رابعًا: نقاط مهمة
- **APP_URL:** يجب أن يطابق النطاق العام، وإلا تخرج بعض الروابط بـ `localhost`.
- **caption_link لكل عدد:** استخدم النطاق العام ليعمل الـ QR عند المستلمين.
- **الأمان:** لا تضع أسرارًا في المستودع؛ استخدم `.env` فقط. غيّر كلمات مرور MySQL الافتراضية قبل الإنتاج.
- **النسخ الاحتياطي:** بيانات MySQL في الحجم `dbdata` وتبقى بعد إيقاف الحاويات.
