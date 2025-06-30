<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_type',
        'ip_address',
        'user_agent',
        'url',
        'user_id',
        'user_type',
        'details',
        'severity',
        'status',
    ];

    protected $casts = [
        'details' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Log a security event
     */
    public static function logEvent(string $eventType, array $data = []): self
    {
        $user = auth()->guard('web')->user() ?? auth()->guard('admin')->user() ?? auth()->guard('owner')->user();
        
        return self::create([
            'event_type' => $eventType,
            'ip_address' => $data['ip'] ?? request()->ip(),
            'user_agent' => $data['user_agent'] ?? request()->userAgent(),
            'url' => $data['url'] ?? request()->fullUrl(),
            'user_id' => $data['user_id'] ?? ($user ? $user->id : null),
            'user_type' => $data['user_type'] ?? ($user ? class_basename($user) : null),
            'details' => $data['details'] ?? [],
            'severity' => $data['severity'] ?? 'medium',
            'status' => $data['status'] ?? 'detected',
        ]);
    }

    /**
     * Severity levels
     */
    public const SEVERITY_LOW = 'low';
    public const SEVERITY_MEDIUM = 'medium';
    public const SEVERITY_HIGH = 'high';
    public const SEVERITY_CRITICAL = 'critical';

    /**
     * Event types
     */
    public const EVENT_FAILED_LOGIN = 'failed_login';
    public const EVENT_SUCCESSFUL_LOGIN = 'successful_login';
    public const EVENT_XSS_ATTEMPT = 'xss_attempt';
    public const EVENT_SQL_INJECTION = 'sql_injection';
    public const EVENT_RATE_LIMIT_EXCEEDED = 'rate_limit_exceeded';
    public const EVENT_IP_BLOCKED = 'ip_blocked';
    public const EVENT_SUSPICIOUS_FILE_UPLOAD = 'suspicious_file_upload';
    public const EVENT_DIRECTORY_TRAVERSAL = 'directory_traversal';
    public const EVENT_ADMIN_ACTION = 'admin_action';
    public const EVENT_OWNER_ACTION = 'owner_action';
}
