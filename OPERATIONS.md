# دليل التشغيل والصيانة — نشرة لبنان

> كل الأوامر تُنفَّذ على **الماك ميني** داخل مجلد المشروع:
> ```
> cd ~/news-lebanon/news-lebanon
> ```

---

## 📁 الملفات المهمة وأماكنها

| الملف | مكانه | وظيفته | يُرفع لـ GitHub؟ |
|------|-------|--------|:---:|
| **`.env`** (الجذر) | `~/news-lebanon/news-lebanon/.env` | إعدادات Docker Compose فقط — **رمز نفق Cloudflare** | ❌ (سرّي) |
| **`src/.env`** | `~/news-lebanon/news-lebanon/src/.env` | إعدادات تطبيق Laravel — **مفتاح Claude، APP_DEBUG، قاعدة البيانات** | ❌ (سرّي) |
| `src/.env.docker` | داخل `src` | قالب يُنشأ منه `src/.env` عند أول تشغيل | ✅ |
| `deploy.sh` | الجذر | نشر يدوي كامل | ✅ |
| `autodeploy.sh` | الجذر | نشر تلقائي (كل دقيقتين عبر cron) | ✅ |
| `docker-compose.yml` | الجذر | الحاويات: app · nginx · mysql · scheduler | ✅ |
| `docker-compose.online.yml` | الجذر | حاوية نفق Cloudflare (النشر أونلاين) | ✅ |

> ⚠️ **تنبيه مهم:** داخل حاوية `app`، المجلد `src` يظهر باسم `/var/www`.
> فـ `src/.env` = `/var/www/.env` عند استخدام `docker compose exec app`.
> **لا تضع أبدًا أقواس `< >` حول أي توكن — التوكن فقط.**

---

## 1️⃣ النشر التلقائي + توكن GitHub للقراءة

الهدف: يسحب السيرفر تحديثاتي من GitHub تلقائيًا (بلا دمج يدوي).

**خطوة يدوية (أنت):** أنشئ **توكن قراءة فقط**:
`https://github.com/settings/personal-access-tokens/new`
- Repository access → **Only select repositories** → `news-lebanon`
- Permissions → Repository → **Contents: Read-only**
- Generate → انسخه.

**الأوامر (بعد وضع التوكن):**
```
git remote set-url origin https://<GITHUB_READ_TOKEN>@github.com/jfalanazi/news-lebanon.git
GIT_TERMINAL_PROMPT=0 git fetch origin main
./autodeploy.sh
crontab -l
```
- `git fetch` يجب أن ينجح **بلا طلب اسم مستخدم**.
- إن لم يظهر سطر autodeploy في `crontab -l`:
```
( crontab -l 2>/dev/null | grep -v autodeploy.sh; echo "*/2 * * * * cd $PWD && /bin/bash autodeploy.sh >> autodeploy.log 2>&1" ) | crontab -
```

---

## 2️⃣ تدوير رمز نفق Cloudflare

**خطوة يدوية (أنت):** `one.dash.cloudflare.com` → Networks → Tunnels → `nashra` → **Refresh token** → انسخ الجديد.

**الأوامر (ملف `.env` بالجذر):**
```
echo 'CLOUDFLARE_TUNNEL_TOKEN=<CLOUDFLARE_TOKEN>' > .env
docker compose -f docker-compose.yml -f docker-compose.online.yml up -d
docker compose ps
```
تأكد أن `nashra_tunnel` حالته **Up**.

---

## 3️⃣ تدوير مفتاح Claude

**خطوة يدوية (أنت):** `console.anthropic.com` → API Keys → احذف القديم → Create key → انسخ الجديد.

**الأوامر (ملف `src/.env` = `/var/www/.env`):**
```
docker compose exec -T app sed -i '/^ANTHROPIC_API_KEY=/d' /var/www/.env
docker compose exec -T app sh -c "echo 'ANTHROPIC_API_KEY=<CLAUDE_KEY>' >> /var/www/.env"
docker compose exec app php artisan config:clear
docker compose exec -T app sh -c "grep ANTHROPIC /var/www/.env"
```

---

## 4️⃣ إقفال وضع التصحيح (أمان الإنتاج)

**الأوامر (ملف `src/.env`):**
```
docker compose exec -T app sed -i 's|^APP_DEBUG=.*|APP_DEBUG=false|' /var/www/.env
docker compose exec app php artisan config:clear
```

---

## 🔧 أوامر يومية مفيدة

```
# متابعة سجل النشر التلقائي
tail -f autodeploy.log

# حالة الحاويات
docker compose ps

# تفريغ الكاش بعد أي تعديل إعداد
docker compose exec app php artisan optimize:clear

# سحب الأخبار يدويًا الآن
docker compose exec app php artisan nashra:fetch

# إنشاء مستخدم لوحة جديد
docker compose exec app php artisan make:filament-user
```

---

## 🔗 روابط سريعة
- اللوحة: `https://nashra.ijaber.com/admin`
- صورة عدد: `https://nashra.ijaber.com/storage/newsletters/edition-<رقم>.png`
