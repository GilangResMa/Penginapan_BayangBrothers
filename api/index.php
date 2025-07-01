<?php

// Set writable paths for Vercel
if (isset($_ENV['VERCEL'])) {
    // Create temp directories
    $tempDirs = ['/tmp/views', '/tmp/cache', '/tmp/sessions'];
    foreach ($tempDirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}

// Set proper server variables
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['REQUEST_URI'] = $_SERVER['REQUEST_URI'] ?? '/';

// Forward to Laravel
require __DIR__ . '/../public/index.php';