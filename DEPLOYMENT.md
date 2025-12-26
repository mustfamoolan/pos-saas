# تعليمات النشر على السيرفر

## خطوات النشر الأساسية

### 1. تثبيت Dependencies

بعد رفع المشروع على السيرفر، يجب تثبيت الحزم المطلوبة:

```bash
# انتقل إلى مجلد المشروع
cd /path/to/your/project

# قم بتثبيت Composer dependencies
composer install --no-dev --optimize-autoloader

# إذا لم يكن Composer مثبتاً، قم بتثبيته أولاً
# أو استخدم: php composer.phar install --no-dev --optimize-autoloader
```

### 2. حل مشكلة "Failed to open stream: No such file or directory"

إذا ظهرت رسالة الخطأ:
```
include(/path/to/vendor/composer/../laravel/framework/...): Failed to open stream: No such file or directory
```

**الحل:**
```bash
# تأكد من وجود Composer
composer --version

# إذا لم يكن موجوداً، قم بتحميله:
curl -sS https://getcomposer.org/installer | php

# ثم قم بتثبيت الحزم:
php composer.phar install --no-dev --optimize-autoloader

# أو إذا كان Composer مثبتاً عالمياً:
composer install --no-dev --optimize-autoloader
```

### 2.1 حل مشكلة "Class Illuminate\Foundation\ComposerScripts is not autoloadable"

إذا ظهرت رسالة الخطأ:
```
Class Illuminate\Foundation\ComposerScripts is not autoloadable, can not call post-autoload-dump script
Script @php artisan package:discover --ansi handling the post-autoload-dump event returned with error code 255
```

**الحل:**

هذا الخطأ يحدث لأن Composer يحاول تشغيل scripts قبل اكتمال تثبيت Laravel Framework. الحل:

```bash
# الطريقة 1: تثبيت بدون scripts أولاً
composer install --no-dev --optimize-autoloader --no-scripts

# ثم بعد اكتمال التثبيت، قم بتشغيل scripts يدوياً:
php artisan package:discover --ansi
php artisan vendor:publish --tag=laravel-assets --ansi --force

# الطريقة 2: إذا استمرت المشكلة، قم بحذف vendor وإعادة التثبيت:
rm -rf vendor composer.lock
composer install --no-dev --optimize-autoloader --no-scripts
php artisan package:discover --ansi

# الطريقة 3: إذا كان هناك مشكلة في composer.lock، قم بتحديثه:
composer update --no-dev --optimize-autoloader --no-scripts
php artisan package:discover --ansi
```

### 2.2 حل مشكلة "Class NunoMaduro\Collision\Adapters\Laravel\CollisionServiceProvider not found"

إذا ظهرت رسالة الخطأ:
```
Class "NunoMaduro\Collision\Adapters\Laravel\CollisionServiceProvider" not found
```

**الحل:**

هذا الخطأ يحدث لأن Laravel يحاول تحميل Collision (وهو حزمة dev فقط) في بيئة Production. الحل:

```bash
# الطريقة 1: تنظيف الـ cache وإعادة التثبيت
php artisan config:clear
php artisan cache:clear
composer dump-autoload --optimize

# الطريقة 2: إذا استمرت المشكلة، قم بحذف ملفات الـ cache:
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/data/*
php artisan config:cache
php artisan route:cache
php artisan view:cache

# الطريقة 3: تأكد من أن composer.json محدث (يجب أن يحتوي على dont-discover لـ collision)
# ثم قم بتشغيل:
composer dump-autoload --optimize
php artisan config:clear
php artisan config:cache
```

### 2.3 حل مشكلة "Class Spatie\LaravelIgnition\IgnitionServiceProvider not found"

إذا ظهرت رسالة الخطأ:
```
Class "Spatie\LaravelIgnition\IgnitionServiceProvider" not found
```

**الحل:**

هذا الخطأ يحدث لأن Laravel يحاول تحميل LaravelIgnition (وهو حزمة dev فقط) في بيئة Production. الحل:

```bash
# الطريقة 1: تنظيف الـ cache وإعادة التثبيت
php artisan config:clear
php artisan cache:clear
composer dump-autoload --optimize --no-dev

# الطريقة 2: إذا استمرت المشكلة، قم بحذف ملفات الـ cache:
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/data/*
php artisan config:cache
php artisan route:cache
php artisan view:cache

# الطريقة 3: تأكد من أن composer.json محدث (يجب أن يحتوي على dont-discover لـ spatie/laravel-ignition)
# ثم قم بتشغيل:
composer dump-autoload --optimize --no-dev
php artisan config:clear
php artisan config:cache
```

### 2.4 حل مشكلة "Class Laravel\Breeze\BreezeServiceProvider not found"

إذا ظهرت رسالة الخطأ:
```
Class "Laravel\Breeze\BreezeServiceProvider" not found
```

**الحل:**

هذا الخطأ يحدث لأن Laravel يحاول تحميل Laravel Breeze (وهو حزمة dev فقط) في بيئة Production. الحل:

```bash
# الطريقة 1: تنظيف الـ cache وإعادة التثبيت
php artisan config:clear
php artisan cache:clear
composer dump-autoload --optimize --no-dev

# الطريقة 2: إذا استمرت المشكلة، قم بحذف ملفات الـ cache:
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/data/*
php artisan config:cache
php artisan route:cache
php artisan view:cache

# الطريقة 3: تأكد من أن composer.json محدث (يجب أن يحتوي على dont-discover لـ laravel/breeze)
# ثم قم بتشغيل:
composer dump-autoload --optimize --no-dev
php artisan config:clear
php artisan config:cache
```

### 2.5 حل عام لجميع مشاكل Service Providers من حزم Dev

إذا ظهرت رسالة خطأ مشابهة:
```
Class "PackageName\ServiceProvider" not found
```

**الحل الشامل:**

تم إضافة جميع حزم `require-dev` إلى `dont-discover` في `composer.json`:
- `nunomaduro/collision`
- `spatie/laravel-ignition`
- `laravel/breeze`
- `laravel/sail`
- `laravel/pint`
- `fakerphp/faker`
- `mockery/mockery`
- `phpunit/phpunit`

**على السيرفر، قم بتنفيذ:**

```bash
# 1. سحب التحديثات
git pull origin main

# 2. تنظيف جميع الـ cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 3. حذف ملفات الـ cache القديمة
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/data/*

# 4. تحديث autoloader
composer dump-autoload --optimize --no-dev

# 5. إعادة بناء الـ cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**ملاحظة:** جميع حزم `require-dev` تم إضافتها إلى `dont-discover` في `composer.json` لمنع هذه المشاكل في المستقبل.

### 2.6 حل مشكلة "Unable to prepare route for serialization. Another route has already been assigned name"

إذا ظهرت رسالة الخطأ:
```
Unable to prepare route [envato/purchase-code/verify/process] for serialization. 
Another route has already been assigned name [LaravelInstaller::codeVerifyProcess].
```

**الحل:**

هذا الخطأ يحدث بسبب وجود route مكرر بنفس الاسم. الحل:

```bash
# 1. افتح الملف التالي للتعديل:
nano vendor/safiull/laravel-installer/src/Routes/web.php

# 2. ابحث عن السطر 122-128 وقم بتغيير:
# من:
Route::group(['prefix' => 'envato', 'as' => 'LaravelInstaller::', ...
# أو
Route::group(['prefix' => 'envato', 'as' => 'LaravelVerifier::', ...

# إلى:
Route::group(['prefix' => 'envato', 'as' => 'LaravelEnvato::', ...

# 3. احفظ الملف (Ctrl+X ثم Y ثم Enter)

# 4. قم بتنظيف الـ cache:
php artisan route:clear
php artisan config:clear
php artisan cache:clear

# 5. قم بإعادة بناء الـ cache:
php artisan route:cache
php artisan config:cache
```

**أو استخدم هذا الأمر السريع:**

```bash
# استبدال مباشر باستخدام sed (على Linux/Mac)
# تغيير السطر 122 فقط من LaravelInstaller:: أو LaravelVerifier:: إلى LaravelEnvato::
sed -i "122s/'as' => 'LaravelInstaller::'/'as' => 'LaravelEnvato::'/" vendor/safiull/laravel-installer/src/Routes/web.php
sed -i "122s/'as' => 'LaravelVerifier::'/'as' => 'LaravelEnvato::'/" vendor/safiull/laravel-installer/src/Routes/web.php

# ثم قم بتنظيف وإعادة بناء الـ cache:
php artisan route:clear
php artisan route:cache
php artisan config:cache
```

**أو استخدم السكريبت التلقائي (الأسهل):**

```bash
# 1. سحب التحديثات من GitHub
git pull origin main

# 2. جعل السكريبت قابل للتنفيذ
chmod +x fix-route-conflict.sh

# 3. تشغيل السكريبت
./fix-route-conflict.sh
```

السكريبت سيقوم تلقائيًا بـ:
- إصلاح اسم الـ route المكرر
- تنظيف جميع الـ caches
- إعادة بناء الـ caches

### 3. حل مشكلة "Please provide a valid cache path"

بعد رفع المشروع على السيرفر، إذا ظهرت رسالة الخطأ:
```
InvalidArgumentException: Please provide a valid cache path.
```

**الحل:**

1. **تأكد من وجود المجلدات التالية:**
```bash
storage/framework/cache/data
storage/framework/sessions
storage/framework/views
storage/logs
bootstrap/cache
```

2. **قم بإنشاء المجلدات إذا لم تكن موجودة:**
```bash
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache
```

3. **قم بتعيين الصلاحيات الصحيحة (775 أو 777):**
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

4. **تأكد من أن مالك المجلدات هو نفس مالك ملفات Laravel:**
```bash
# على Linux/Mac
chown -R www-data:www-data storage bootstrap/cache

# أو إذا كان المستخدم مختلف
chown -R your-user:your-group storage bootstrap/cache
```

5. **قم بتشغيل الأوامر التالية:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

6. **تأكد من إعدادات ملف .env:**
```env
CACHE_DRIVER=file
SESSION_DRIVER=file
```

### 4. إعداد ملف .env

```bash
# انسخ ملف .env.example إلى .env
cp .env.example .env

# قم بتعديل ملف .env وإضافة إعدادات قاعدة البيانات والمعلومات المطلوبة
nano .env

# قم بتوليد مفتاح التطبيق
php artisan key:generate
```

### 5. إعداد قاعدة البيانات

```bash
# قم بتشغيل Migrations
php artisan migrate --force

# قم بتشغيل Seeders (اختياري)
php artisan db:seed --force
```

### 6. تحسين الأداء (Production)

```bash
# قم بتخزين الإعدادات والـ routes في الـ cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# قم بتحسين autoloader
composer dump-autoload --optimize
```

### ملاحظات مهمة:

- تأكد من أن مجلد `storage` و `bootstrap/cache` لديهم صلاحيات الكتابة
- إذا كنت تستخدم shared hosting، قد تحتاج لاستخدام صلاحيات 755 بدلاً من 775
- تأكد من أن PHP يمكنه الكتابة في هذه المجلدات
- تأكد من تثبيت جميع Composer dependencies قبل تشغيل المشروع
- في بيئة Production، استخدم `--no-dev` مع composer install

