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
3. **شغّل النشر (يهيّئ كل شي تلقائيًا):**
   ```
   ./deploy.sh
   ```
   يبني الحاويات، ينشئ `src/.env` من `src/.env.docker`، يركّب `composer install`، يولّد المفتاح، يرحّل قاعدة البيانات، ويربط التخزين.
4. **أنشئ مستخدم اللوحة (أول مرة فقط):**
   ```
   docker compose exec app php artisan make:filament-user
   ```
5. **للإنتاج:** في `src/.env` عدّل `APP_ENV=production` و`APP_DEBUG=false` و`APP_URL=https://nashra.ijaber.com`، ثم `docker compose exec app php artisan optimize:clear`.

---

## ثانيًا: دورة العمل اليومية

على الماك بوك (معي): نحرّر ونرفع تلقائيًا لـ GitHub.
على الماك البعيد، للنشر بأمر واحد:

```
./deploy.sh
```

يسحب آخر نسخة، يعيد البناء، ويهيّئ التطبيق. (لأتمتته لاحقًا: cron يشغّل `deploy.sh` كل ساعة، أو GitHub webhook.)

---

## ثالثًا: النشر أونلاين — `ijaber.com` عبر Cloudflare Tunnel

### 1) انقل الدومين لإدارة Cloudflare (مرة واحدة)
1. أنشئ حساب **Cloudflare** مجاني → **Add a site** → `ijaber.com`.
2. Cloudflare يفحص السجلات ويعطيك **زوج name servers** (مثل `xxx.ns.cloudflare.com`).
3. في **Namecheap**: Domain List → `ijaber.com` → **Manage** → قسم **Nameservers** → اختر **Custom DNS** → الصق الـ nameservers من Cloudflare → احفظ. (يسري خلال دقائق إلى ساعات.)
4. ⚠️ **إن كان `ijaber.com` يُستخدم لبريد M365:** تأكد أن سجلات **MX / TXT(SPF) / CNAME(DKIM/autodiscover)** موجودة في Cloudflare قبل التبديل (يستوردها Cloudflare غالبًا تلقائيًا) — وإلا يتعطّل بريدك.

### 2) أنشئ النفق واربطه بالنطاق الفرعي
1. **Cloudflare Zero Trust → Networks → Tunnels → Create a tunnel** (نوع Cloudflared) → سمِّه `nashra` → انسخ الـ **Token**.
2. أضف السطر في `.env` بجذر المشروع على الماك ميني:
   ```
   CLOUDFLARE_TUNNEL_TOKEN=<التوكن>
   ```
3. في **Public Hostname** للنفق:
   - Subdomain: `nashra` — Domain: `ijaber.com`
   - Service: `http://nginx:80`
4. شغّل الطبقة العامة على الماك ميني:
   ```
   docker compose -f docker-compose.yml -f docker-compose.online.yml up -d
   ```

**النتيجة:** اللوحة على **https://nashra.ijaber.com/admin**، وروابط QR/الصور تعمل للجمهور.

> **للتجربة الفورية قبل نقل الـ nameservers:** نفق مؤقت يعطيك رابط `*.trycloudflare.com`:
> ```
> cloudflared tunnel --url http://localhost:8080
> ```

---

## رابعًا: نقاط مهمة
- **APP_URL:** يجب أن يطابق النطاق العام، وإلا تخرج بعض الروابط بـ `localhost`.
- **caption_link لكل عدد:** استخدم النطاق العام ليعمل الـ QR عند المستلمين.
- **الأمان:** لا تضع أسرارًا في المستودع؛ استخدم `.env` فقط. غيّر كلمات مرور MySQL الافتراضية قبل الإنتاج.
- **النسخ الاحتياطي:** بيانات MySQL في الحجم `dbdata` وتبقى بعد إيقاف الحاويات.
