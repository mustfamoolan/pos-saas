# تعليمات النشر على السيرفر

## حل مشكلة "Please provide a valid cache path"

بعد رفع المشروع على السيرفر، إذا ظهرت رسالة الخطأ:
```
InvalidArgumentException: Please provide a valid cache path.
```

### الحل:

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

أو على Windows (إذا كنت تستخدم WSL):
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
```

6. **تأكد من إعدادات ملف .env:**
```env
CACHE_DRIVER=file
SESSION_DRIVER=file
```

### ملاحظات مهمة:

- تأكد من أن مجلد `storage` و `bootstrap/cache` لديهم صلاحيات الكتابة
- إذا كنت تستخدم shared hosting، قد تحتاج لاستخدام صلاحيات 755 بدلاً من 775
- تأكد من أن PHP يمكنه الكتابة في هذه المجلدات

