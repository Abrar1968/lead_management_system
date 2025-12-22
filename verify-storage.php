#!/usr/bin/env php
<?php

/**
 * Storage Verification Script for Hostinger Deployment
 *
 * Run this after deployment to verify storage configuration
 * Usage: php verify-storage.php
 */
echo "🔍 Laravel Storage Verification\n";
echo "================================\n\n";

// Check if we're in Laravel root
if (! file_exists('artisan')) {
    echo "❌ ERROR: Run this script from Laravel root directory\n";
    exit(1);
}

$checks = [];

// 1. Check storage symlink
echo "1. Checking storage symlink...\n";
$symlinkPath = 'public/storage';
if (is_link($symlinkPath)) {
    $target = readlink($symlinkPath);
    echo "   ✅ Symlink exists: $symlinkPath -> $target\n";
    $checks['symlink'] = true;
} else {
    echo "   ❌ Symlink missing! Run: php artisan storage:link\n";
    $checks['symlink'] = false;
}

// 2. Check storage directories
echo "\n2. Checking storage directories...\n";
$dirs = [
    'storage/app/public',
    'storage/app/public/clients',
    'storage/app/public/clients/documents',
    'storage/app/public/demos',
    'storage/app/public/demos/documents',
];

foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        $writable = is_writable($dir) ? '✅' : '❌';
        echo "   $writable $dir (permissions: $perms)\n";
        $checks[$dir] = is_writable($dir);
    } else {
        echo "   ⚠️  $dir (not created yet)\n";
        $checks[$dir] = 'pending';
    }
}

// 3. Check .env configuration
echo "\n3. Checking .env configuration...\n";
if (file_exists('.env')) {
    $env = file_get_contents('.env');

    // Check FILESYSTEM_DISK
    if (preg_match('/FILESYSTEM_DISK=(\w+)/', $env, $matches)) {
        $disk = $matches[1];
        if ($disk === 'public') {
            echo "   ✅ FILESYSTEM_DISK=public\n";
            $checks['filesystem_disk'] = true;
        } else {
            echo "   ⚠️  FILESYSTEM_DISK=$disk (recommended: public)\n";
            $checks['filesystem_disk'] = false;
        }
    } else {
        echo "   ⚠️  FILESYSTEM_DISK not set (will use default: local)\n";
        $checks['filesystem_disk'] = false;
    }

    // Check APP_URL
    if (preg_match('/APP_URL=(.+)/', $env, $matches)) {
        $url = trim($matches[1]);
        echo "   ℹ️  APP_URL=$url\n";
    }
} else {
    echo "   ❌ .env file not found!\n";
    $checks['.env'] = false;
}

// 4. Check bootstrap/cache
echo "\n4. Checking bootstrap/cache...\n";
if (is_writable('bootstrap/cache')) {
    echo "   ✅ bootstrap/cache is writable\n";
    $checks['bootstrap_cache'] = true;
} else {
    echo "   ❌ bootstrap/cache is not writable! Run: chmod -R 775 bootstrap/cache\n";
    $checks['bootstrap_cache'] = false;
}

// 5. Check storage/logs
echo "\n5. Checking storage/logs...\n";
if (is_writable('storage/logs')) {
    echo "   ✅ storage/logs is writable\n";
    $checks['storage_logs'] = true;
} else {
    echo "   ❌ storage/logs is not writable! Run: chmod -R 775 storage\n";
    $checks['storage_logs'] = false;
}

// Summary
echo "\n".str_repeat('=', 50)."\n";
echo "Summary:\n";
$passed = count(array_filter($checks, fn ($v) => $v === true));
$failed = count(array_filter($checks, fn ($v) => $v === false));
$pending = count(array_filter($checks, fn ($v) => $v === 'pending'));

echo "✅ Passed: $passed\n";
echo "❌ Failed: $failed\n";
echo "⚠️  Pending: $pending\n";

if ($failed === 0) {
    echo "\n🎉 All critical checks passed! Storage is properly configured.\n";
    exit(0);
} else {
    echo "\n⚠️  Some issues found. Please fix them before testing file uploads.\n";
    exit(1);
}
