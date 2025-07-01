<?php

/*
|--------------------------------------------------------------------------
| Vercel Bootstrap Configuration
|--------------------------------------------------------------------------
*/

// Set environment for Vercel
if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
}

// Force writable paths to /tmp for Vercel
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/views';
$_ENV['CACHE_PATH'] = '/tmp/cache';
$_ENV['SESSION_FILE_PATH'] = '/tmp/sessions';
$_ENV['LOG_CHANNEL'] = 'stderr';

// Create necessary directories
$dirs = ['/tmp/views', '/tmp/cache', '/tmp/sessions'];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Include the normal bootstrap
require __DIR__ . '/app.php';
