# PHP 8.3 + Node 22 + Chromium — لتوليد صور النشرة (Browsershot)
# مضبوط لـ Apple Silicon (M4/ARM64): نثبّت Chromium عبر apt بدل تنزيله عبر Puppeteer.
FROM php:8.3-fpm

# أدوات وإضافات PHP
RUN apt-get update && apt-get install -y --no-install-recommends \
    git curl unzip zip \
    libzip-dev libpng-dev libonig-dev libxml2-dev libicu-dev \
 && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd intl \
 && rm -rf /var/lib/apt/lists/*

# Node.js 22 (يحتاجه Puppeteer)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
 && apt-get install -y --no-install-recommends nodejs \
 && rm -rf /var/lib/apt/lists/*

# Chromium + خطوط عربية (متوافق مع ARM64 عبر apt)
RUN apt-get update && apt-get install -y --no-install-recommends \
    chromium fonts-liberation fonts-noto-core \
 && rm -rf /var/lib/apt/lists/*

# نمنع Puppeteer من تنزيل Chromium (نستخدم Chromium النظام)
ENV PUPPETEER_SKIP_DOWNLOAD=true
ENV CHROME_PATH=/usr/bin/chromium

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
