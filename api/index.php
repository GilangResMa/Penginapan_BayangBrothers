<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Set writable paths for Vercel
$tempDirs = ['/tmp/views', '/tmp/cache', '/tmp/sessions', '/tmp/logs'];
foreach ($tempDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Override environment variables for Vercel
putenv('VIEW_COMPILED_PATH=/tmp/views');
putenv('CACHE_PATH=/tmp/cache');
putenv('SESSION_FILE_PATH=/tmp/sessions');

// Register the Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Handle the request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);