# Quick Hostinger Deployment Guide

## Step 1: Upload Files to Hostinger

### Via Git (Recommended)
```bash
# On your local machine
git add .
git commit -m "Fix notes nullable, validation messages, and 403 errors"
git push origin main

# On Hostinger via SSH
cd /home/username/public_html
git pull origin main
```

### Via FTP/File Manager
Upload these specific files:
```
database/migrations/2025_12_23_053123_make_notes_nullable_in_follow_ups_table.php
app/Http/Requests/StoreLeadRequest.php
app/Http/Requests/UpdateLeadRequest.php
public/.htaccess  ⚠️ CRITICAL - Must include +FollowSymLinks!
storage/app/public/.htaccess  ⚠️ IMPORTANT - ensure hidden files are shown!
```

---

## Step 2: Run Commands on Hostinger

### A. Access SSH Terminal
In cPanel → Advanced → Terminal, or via SSH client:
```bash
ssh username@yourdomain.com
```

### B. Navigate to Project
```bash
cd public_html
# or if in subdirectory:
cd public_html/lead_ms
```

### C. Run Migration
```bash
php artisan migrate
```

**Expected Output:**
```
Running migrations.
2025_12_23_053123_make_notes_nullable_in_follow_ups_table ........ DONE
```

### D. Ensure Storage Link Exists
```bash
php artisan storage:link
```

**Expected Output:**
```
The [public/storage] link has been connected to [storage/app/public].
```

### E. Set File Permissions (if needed)
```bash
chmod -R 755 storage/app/public
find storage/app/public -type f -exec chmod 644 {} \;
```

### F. Clear All Caches
```bash
php artisan optimize:clear
```

---

## Step 3: Verify .htaccess File Uploaded

### Check if file exists:
```bash
ls -la storage/app/public/.htaccess
```

**Should show:**
```
-rw-r--r-- 1 username username 850 Dec 23 12:00 .htaccess
```

### If file is missing:
1. In File Manager, enable "Show Hidden Files"
2. Navigate to `storage/app/public/`
3. Upload `.htaccess` file manually

---

## Step 4: Test the Fixes

### 1. Test Notes Field (Follow-Up)
- Go to Leads → Select a lead → Add Follow-Up
- Leave "Notes" field empty
- Click Save
- ✅ **Should save successfully without error**

### 2. Test Validation Messages
- Go to Leads → Create New Lead
- Leave "Phone Number" blank
- Click Save
- ✅ **Should show:** "Phone number is required. Please enter the client's phone number."

### 3. Test Image Loading
- Go to Demos or Clients
- View a record with uploaded image
- ✅ **Image should display, not 403 error**

### 4. Test Document Preview
- Upload a PDF document to a demo
- Click to preview
- ✅ **PDF should open in browser (or download based on browser settings)**

---

## Troubleshooting

### Issue: Migration Error "Table doesn't exist"
**Solution:**
```bash
php artisan migrate:status
# If follow_ups shows "Ran? N", run:
php artisan migrate
```

### Issue: Still Getting 403 on Images
**Check these:**

1. **FollowSymLinks enabled in public/.htaccess?**
   ```bash
   grep -i "FollowSymLinks" public/.htaccess
   # Should show: Options +FollowSymLinks
   ```

2. **.htaccess files uploaded?**
   ```bash
   # Check public
   cat public/.htaccess
   
   # Check storage
   cat storage/app/public/.htaccess
   # Should show file content
   ```

2. **Permissions correct?**
   ```bash
   ls -la storage/app/public/demos/
   # Files should be 644, directories 755
   ```

3. **Storage link working?**
   ```bash
   ls -la public/storage
   # Should show: storage -> ../storage/app/public
   ```

4. **Try direct URL in browser:**
   ```
   https://yourdomain.com/storage/test.txt
   ```
   Create test file:
   ```bash
   echo "Test file" > storage/app/public/test.txt
   chmod 644 storage/app/public/test.txt
   ```

### Issue: Validation Messages Not Showing
**Clear cache:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Issue: Permission Denied Errors
**Fix ownership (ask Hostinger support for correct user):**
```bash
# Usually one of these:
chown -R username:username storage/app/public
# or
chown -R nobody:nobody storage/app/public
```

---

## Cloudflare Users

If using Cloudflare CDN:
1. Login to Cloudflare Dashboard
2. Go to Caching → Configuration
3. Click "Purge Everything"
4. Wait 5 minutes
5. Test again

---

## Verification Checklist

After deployment, check all these:

- [ ] SSH access working
- [ ] Migration ran successfully
- [ ] `public/storage` symlink exists
- [ ] `.htaccess` file exists in `storage/app/public/`
- [ ] Can create follow-up without notes
- [ ] Validation messages are user-friendly
- [ ] Images load without 403 error
- [ ] Documents preview correctly
- [ ] File uploads still working

---

## Contact Hostinger Support If:

1. **Can't create storage link** → They need to enable symlink functionality
2. **Permission denied constantly** → They need to check web server user
3. **mod_headers not available** → They need to enable Apache module
4. **.htaccess not working** → They may have disabled it (unlikely)

**Support Phrase:** "I need symlink support enabled and mod_headers Apache module for Laravel storage."

---

## Success Indicators

You'll know everything is working when:

✅ Follow-ups save without notes field
✅ Error messages guide users clearly
✅ Images display in all views
✅ No 403 errors in browser console
✅ Documents open inline or download cleanly

---

## Rollback (If Needed)

If something breaks:

```bash
# Rollback migration
php artisan migrate:rollback --step=1

# Remove .htaccess
rm storage/app/public/.htaccess

# Restore previous code
git reset --hard HEAD~1
```

---

**Need help? Check the detailed FIXES_SUMMARY.md file for more information.**
