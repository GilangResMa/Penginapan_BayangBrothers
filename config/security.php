<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains security settings for the application including
    | rate limiting, IP blocking, and other security measures.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    */
    'rate_limiting' => [
        'web' => [
            'max_attempts' => 120,
            'decay_minutes' => 1,
        ],
        'auth' => [
            'max_attempts' => 30,
            'decay_minutes' => 1,
        ],
        'api' => [
            'max_attempts' => 60,
            'decay_minutes' => 1,
        ],
        'admin' => [
            'max_attempts' => 60,
            'decay_minutes' => 1,
        ],
        'owner' => [
            'max_attempts' => 60,
            'decay_minutes' => 1,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Security
    |--------------------------------------------------------------------------
    */
    'file_upload' => [
        'max_size' => 5 * 1024 * 1024, // 5MB
        'allowed_mime_types' => [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
        ],
        'allowed_extensions' => [
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'
        ],
        'blocked_extensions' => [
            'php', 'exe', 'bat', 'sh', 'cmd', 'scr', 'pif', 'jar', 'js', 'html', 'htm'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IP Blocking Configuration
    |--------------------------------------------------------------------------
    */
    'ip_blocking' => [
        'enabled' => env('IP_BLOCKING_ENABLED', false),
        'blocked_ips' => [
            // Add blocked IP addresses here
        ],
        'allowed_ips' => [
            // Add allowed IP addresses here (for admin access)
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Security Policy
    |--------------------------------------------------------------------------
    */
    'csp' => [
        'default_src' => "'self'",
        'script_src' => "'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com https://app.midtrans.com https://app.stg.midtrans.com",
        'style_src' => "'self' 'unsafe-inline' https://cdnjs.cloudflare.com",
        'img_src' => "'self' data: https: http:",
        'font_src' => "'self' https://cdnjs.cloudflare.com",
        'connect_src' => "'self' https://api.midtrans.com https://api.stg.midtrans.com",
        'frame_src' => "'self' https://app.midtrans.com https://app.stg.midtrans.com",
        'object_src' => "'none'",
        'base_uri' => "'self'",
        'form_action' => "'self'",
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Headers
    |--------------------------------------------------------------------------
    */
    'headers' => [
        'x_content_type_options' => 'nosniff',
        'x_frame_options' => 'DENY',
        'x_xss_protection' => '1; mode=block',
        'referrer_policy' => 'strict-origin-when-cross-origin',
        'permissions_policy' => [
            'camera' => '()',
            'microphone' => '()',
            'geolocation' => '()',
            'interest_cohort' => '()',
            'payment' => '(self)',
            'usb' => '()',
            'magnetometer' => '()',
            'accelerometer' => '()',
            'gyroscope' => '()',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTPS Configuration
    |--------------------------------------------------------------------------
    */
    'https' => [
        'force_https' => env('FORCE_HTTPS', false),
        'hsts_enabled' => env('HSTS_ENABLED', false),
        'hsts_max_age' => 31536000, // 1 year
        'hsts_include_subdomains' => true,
        'hsts_preload' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Input Validation
    |--------------------------------------------------------------------------
    */
    'input_validation' => [
        'max_input_length' => 10000,
        'block_sql_injection' => true,
        'block_xss' => true,
        'block_directory_traversal' => true,
        'strip_html_tags' => false, // Set to true to strip HTML tags from input
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Security
    |--------------------------------------------------------------------------
    */
    'session' => [
        'secure_cookies' => env('SESSION_SECURE_COOKIES', false),
        'http_only_cookies' => true,
        'same_site_cookies' => 'strict',
        'session_timeout' => 120, // minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'log_failed_logins' => true,
        'log_suspicious_activity' => true,
        'log_file_uploads' => true,
        'log_admin_actions' => true,
    ],
];
