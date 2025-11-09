# File Upload Configuration Fix

## Problem
PHP is limiting file uploads to 2MB due to default `php.ini` settings.

## Solution

### Option 1: Update php.ini (Recommended)

1. **Open php.ini file** at: `C:\Program Files\php-8.4.13\php.ini`

2. **Find and update these lines:**
   ```ini
   upload_max_filesize = 50M
   post_max_size = 100M
   max_file_uploads = 20
   memory_limit = 256M
   ```

3. **Restart your development server:**
   ```bash
   # Stop the current server (Ctrl+C)
   # Then restart:
   php artisan serve
   ```

### Option 2: Laravel .htaccess (Alternative)

If you can't edit php.ini, add to `public/.htaccess`:

```apache
php_value upload_max_filesize 50M
php_value post_max_size 100M
php_value max_file_uploads 20
php_value memory_limit 256M
```

**Note:** This only works with Apache + mod_php.

### Option 3: Runtime Configuration (Not Recommended)

This won't work for upload limits but can be added to `public/index.php` for memory:

```php
ini_set('memory_limit', '256M');
```

## Verify Changes

After making changes, verify with:

```bash
php -r "echo 'upload_max_filesize: ' . ini_get('upload_max_filesize') . PHP_EOL; echo 'post_max_size: ' . ini_get('post_max_size') . PHP_EOL;"
```

Expected output:
```
upload_max_filesize: 50M
post_max_size: 100M
```

## What We Changed in Code

### Backend (ProjectController.php)
- Increased max file size from 10MB to 50MB
- Added support for all common file types including:
  - Documents: pdf, doc, docx, ppt, pptx, xls, xlsx
  - Images: jpg, png, gif, svg, webp, bmp
  - Videos: mp4, avi, mov, mkv, webm
  - Archives: zip, rar, 7z
  - Code files: html, css, js, php, py, java, etc.

### Frontend (files.blade.php)
- Updated UI to show "Max 50MB each"
- Added client-side validation to check file sizes before upload
- Shows helpful error message if files exceed limit

## Current Supported File Types

**Documents:** pdf, doc, docx, xls, xlsx, ppt, pptx, txt, csv  
**Images:** jpg, jpeg, png, gif, svg, webp, bmp, ico  
**Videos:** mp3, mp4, avi, mov, wmv, flv, mkv, webm, wav, ogg  
**Design:** psd, ai, eps, indd, sketch, fig  
**Archives:** zip, rar, 7z  
**Code:** json, xml, html, css, js, php, py, java, cpp, c, h, md, log

## Testing

After updating php.ini:

1. Upload a file larger than 2MB (but less than 50MB)
2. Upload multiple file types (pdf, mp4, pptx, png)
3. Check that all files upload successfully

## Troubleshooting

**Still getting errors?**
- Make sure you restarted the server after editing php.ini
- Check Laravel logs: `storage/logs/laravel.log`
- Verify php.ini changes: `php --ini` to confirm file location
- Clear Laravel cache: `php artisan config:clear`
