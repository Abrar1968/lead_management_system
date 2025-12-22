# Hostinger Deployment Guide

## Pre-Deployment Checklist

### 1. Environment Configuration
Update your `.env` file on Hostinger:

```env
APP_NAME="Lead Management System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database Configuration (from Hostinger panel)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Session & Cache
SESSION_DRIVER=database
CACHE_DRIVER=file
QUEUE_CONNECTION=database

# File Storage
FILESYSTEM_DISK=public
```

### 2. Required Artisan Commands (Run in ORDER)

```bash
# 1. Install dependencies
composer install --optimize-autoloader --no-dev

# 2. Generate application key
php artisan key:generate

# 3. Run database migrations
php artisan migrate --force

# 4. Seed initial data (if needed)
php artisan db:seed --force

# 5. **CRITICAL** Create storage symlink
php artisan storage:link

# 6. Clear and cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Set proper permissions
chmod -R 755 storage bootstrap/cache
```

### 3. Verify Storage Symlink

After running `php artisan storage:link`, verify:

```bash
# Check if symlink exists
ls -la public/storage

# Should show: public/storage -> ../storage/app/public
```

**If symlink fails on Hostinger:**
Some shared hosting doesn't allow symlinks. Contact Hostinger support or use this workaround:

```bash
# Remove broken symlink if exists
rm -f public/storage

# Create proper symlink manually
ln -s ../storage/app/public public/storage
```

### 4. Directory Permissions

Ensure these directories are writable:

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod -R 755 public/storage
```

### 5. File Upload Settings

Check Hostinger's PHP settings:
- `upload_max_filesize` = 10M (minimum)
- `post_max_size` = 10M (minimum)
- `memory_limit` = 256M (recommended)
- `max_execution_time` = 300 (for large uploads)

Update in `.htaccess` if needed:

```apache
php_value upload_max_filesize 10M
php_value post_max_size 10M
php_value memory_limit 256M
php_value max_execution_time 300
```

### 6. Important File Paths

All file operations now use Laravel's Storage facade:

- **Client Images**: `storage/app/public/clients/`
- **Client Documents**: `storage/app/public/clients/documents/`
- **Demo Images**: `storage/app/public/demos/`
- **Demo Documents**: `storage/app/public/demos/documents/`

Files are accessed via: `yourdomain.com/storage/clients/filename.jpg`

### 7. Testing After Deployment

1. **Test Image Upload (Client)**:
   - Go to Clients section
   - Create/edit a client with image field
   - Upload an image
   - Verify it displays correctly

2. **Test Document Upload (Client)**:
   - Upload a PDF/DOC file
   - Click "View Document"
   - Should preview in new tab (not download)

3. **Test Demo Fields**:
   - Same tests for Demo section
   - Verify both image and document previews work

4. **Test File Deletion**:
   - Remove an uploaded image
   - Remove an uploaded document
   - Verify files are deleted from server

### 8. Common Hostinger Issues & Fixes

#### Issue: "storage/app/public not accessible"
**Fix**: Run `php artisan storage:link` again

#### Issue: "Images not showing"
**Fix**: 
```bash
# Check symlink
ls -la public/storage

# If broken, recreate
rm public/storage
php artisan storage:link
```

#### Issue: "Permission denied" errors
**Fix**:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### Issue: "File upload fails silently"
**Fix**: Check PHP upload limits in Hostinger panel

#### Issue: "Document preview downloads instead of showing"
**Fix**: This is now handled properly with our preview routes. If still happens, check MIME type support on server.

### 9. Security Checklist

✅ `APP_DEBUG=false` in production
✅ `APP_ENV=production`
✅ Strong `APP_KEY` generated
✅ Database credentials secured
✅ `.env` file not in public folder
✅ `storage/` directory not publicly accessible (only through symlink)

### 10. Performance Optimization

```bash
# After deployment, cache everything
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Enable OPcache in Hostinger PHP settings
```

### 11. Monitoring

After deployment, monitor:
- Upload functionality (images & documents)
- File preview functionality
- Storage disk space usage
- Error logs in `storage/logs/laravel.log`

### 12. Rollback Plan

If issues occur:
1. Backup database before migration
2. Keep previous version code accessible
3. Clear all caches: `php artisan cache:clear`
4. Check error logs: `tail -f storage/logs/laravel.log`

---

## Quick Command Reference

```bash
# View Laravel logs
tail -f storage/logs/laravel.log

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Re-cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Check storage link
ls -la public/storage

# Check permissions
ls -la storage bootstrap/cache
```

---

## Support

If you encounter issues:
1. Check `storage/logs/laravel.log`
2. Verify storage symlink exists
3. Check file permissions (755/775)
4. Contact Hostinger support for PHP/server issues
