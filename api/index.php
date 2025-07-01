<?php

// Set proper server variables
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['REQUEST_URI'] = $_SERVER['REQUEST_URI'] ?? '/';

// Forward to Laravel
require __DIR__ . '/../public/index.php';