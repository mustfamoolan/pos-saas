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

