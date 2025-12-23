# Understanding .htaccess Files in Your Laravel Application

## Two Different .htaccess Files Explained

Your Laravel application uses **TWO** `.htaccess` files in different locations, each serving a different purpose:

---

## 1. `public/.htaccess` ⭐ MAIN FILE

**Location:** `f:\projects\lead_ms\public\.htaccess`

**Purpose:** Controls ALL web requests to your application

**What it does:**
- Routes all web traffic through `index.php` (Laravel's front controller)
- Handles authentication headers
- Redirects trailing slashes
- **CRITICAL:** Must have `Options +FollowSymLinks` to allow the `public/storage` symlink to work

### ✅ UPDATED Configuration:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Allow symlinks (REQUIRED for storage link on Hostinger)
    Options +FollowSymLinks

    # ... rest of standard Laravel config
</IfModule>

# Proper MIME types for images/documents
<IfModule mod_mime.c>
    AddType image/jpeg .jpg .jpeg
    AddType image/png .png
    AddType image/gif .gif
    AddType image/webp .webp
    AddType application/pdf .pdf
</IfModule>
```

**Key Addition:** `Options +FollowSymLinks` - This is CRITICAL for Hostinger!

---

## 2. `storage/app/public/.htaccess` 🔒 PERMISSIONS FILE

**Location:** `f:\projects\lead_ms\storage\app\public\.htaccess`

**Purpose:** Controls access to files in the storage directory

**What it does:**
- Disables rewrite engine (files served directly, not through Laravel)
- Sets proper permissions for web server to read files
- Configures MIME types at the storage level
- Grants access with `Require all granted`

### ✅ Current Configuration:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine Off  # Don't route through Laravel
</IfModule>

<IfModule mod_headers.c>
    # Set proper MIME types
    <FilesMatch "\.(jpg|jpeg|png|gif|webp)$">
        Header set Content-Type "image/*"
    </FilesMatch>
    # ... more MIME types
</IfModule>

# CRITICAL: Allow web server to access files
<FilesMatch ".*">
    Require all granted
</FilesMatch>
```

---

## How They Work Together

### Request Flow for Image:

```
User Browser
    ↓
1. Requests: https://yourdomain.com/storage/demos/image.jpg
    ↓
2. public/.htaccess checks:
   - Is this a real file? (via symlink public/storage → storage/app/public)
   - YES: Serve it directly (with +FollowSymLinks enabled)
   - NO: Would route through index.php
    ↓
3. Web server follows symlink to: storage/app/public/demos/image.jpg
    ↓
4. storage/app/public/.htaccess:
   - Checks permissions: "Require all granted" ✅
   - Sets MIME type: "image/*" ✅
    ↓
5. Image served to browser ✅
```

---

## Why Both Are Needed on Hostinger

### Hostinger's Shared Hosting Restrictions:

1. **Symlinks often disabled by default**
   - `Options +FollowSymLinks` in `public/.htaccess` enables them

2. **Strict permission controls**
   - `Require all granted` in `storage/app/public/.htaccess` explicitly allows access

3. **MIME type issues**
   - Both files set MIME types at different levels for redundancy

---

## Common Hostinger Issues & Solutions

### Issue 1: 403 Forbidden on Images
**Cause:** Missing `Options +FollowSymLinks` in `public/.htaccess`
**Solution:** ✅ FIXED - Added in the update above

### Issue 2: Symlink not working
**Cause:** Hostinger disabled symlinks for your account
**Solution:**
```bash
# Contact Hostinger Support:
"Please enable symlink support (FollowSymLinks) for my account"

# Alternative: Copy files instead of symlink
rm public/storage
cp -r storage/app/public public/storage
# (Not recommended - files won't auto-update)
```

### Issue 3: Files exist but still 403
**Cause:** File permissions or missing `storage/app/public/.htaccess`
**Solution:**
```bash
# 1. Check .htaccess exists
ls -la storage/app/public/.htaccess

# 2. Set permissions
chmod 644 storage/app/public/.htaccess
chmod -R 755 storage/app/public
find storage/app/public -type f -exec chmod 644 {} \;
```

---

## Testing on Hostinger

### After uploading both .htaccess files:

**Test 1: Check Symlink**
```bash
ls -la public/storage
# Should show: storage -> ../storage/app/public
```

**Test 2: Upload Test Image**
```bash
echo "test" > storage/app/public/test.txt
chmod 644 storage/app/public/test.txt
```

**Test 3: Access via Browser**
```
https://yourdomain.com/storage/test.txt
# Should show "test", not 403
```

**Test 4: Check .htaccess Files**
```bash
# Check public
cat public/.htaccess | grep FollowSymLinks
# Should show: Options +FollowSymLinks

# Check storage
cat storage/app/public/.htaccess | grep "Require all granted"
# Should show: Require all granted
```

---

## What You Need to Upload to Hostinger

### Files to Upload:
1. ✅ `public/.htaccess` (UPDATED - now includes +FollowSymLinks)
2. ✅ `storage/app/public/.htaccess` (NEW - for permissions)

### Commands to Run on Hostinger:
```bash
# 1. Ensure symlink exists
php artisan storage:link

# 2. Set correct permissions
chmod 644 public/.htaccess
chmod 644 storage/app/public/.htaccess
chmod -R 755 storage/app/public

# 3. Clear cache
php artisan optimize:clear
```

---

## Comparison Table

| Aspect | public/.htaccess | storage/app/public/.htaccess |
|--------|------------------|------------------------------|
| **Purpose** | Route web requests | Control file access |
| **Rewrite Engine** | ON (route to Laravel) | OFF (serve files directly) |
| **FollowSymLinks** | Required (enabled) | Not needed |
| **MIME Types** | Via mod_mime | Via mod_headers |
| **Permissions** | Inherits from Apache | Explicitly grants access |
| **Critical For** | Laravel routing | Storage file access |

---

## Why Your Original public/.htaccess Might Fail on Hostinger

### Original (Missing FollowSymLinks):
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes  # ❌ No +FollowSymLinks
    </IfModule>
    # ...
</IfModule>
```

**Result on Hostinger:**
- Symlinks disabled by default
- `public/storage → storage/app/public` link doesn't work
- Web server can't find files
- **403 Forbidden Error** ❌

### Updated (With FollowSymLinks):
```apache
<IfModule mod_rewrite.c>
    Options +FollowSymLinks  # ✅ Enables symlinks
    # ...
</IfModule>
```

**Result on Hostinger:**
- Symlinks enabled
- `public/storage` link works
- Files served correctly
- **Images load** ✅

---

## Final Checklist for Hostinger

Before deploying:
- [ ] `public/.htaccess` has `Options +FollowSymLinks`
- [ ] `storage/app/public/.htaccess` exists
- [ ] Both files uploaded to server
- [ ] `php artisan storage:link` executed
- [ ] Permissions set (755 for dirs, 644 for files)
- [ ] Test image loading in browser

---

## If Symlinks Are Completely Disabled

Some shared hosting providers completely disable symlinks. If that's the case:

### Alternative Solution (Not Recommended):
```bash
# Remove symlink
rm public/storage

# Copy files directly
cp -r storage/app/public/* public/storage/

# Note: You'll need to manually copy files after each upload
# This breaks Laravel's storage system design
```

### Better Solution:
Ask Hostinger support to enable symlinks, or upgrade to a plan that supports them.

---

## Summary

✅ **public/.htaccess** - Now includes `Options +FollowSymLinks` for Hostinger
✅ **storage/app/public/.htaccess** - Grants permissions for file access
✅ Both work together to serve images/documents without 403 errors

**The fix is complete. Upload both files and run the commands above on Hostinger!**
