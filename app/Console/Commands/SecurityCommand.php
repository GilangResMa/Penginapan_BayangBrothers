<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SecurityCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:manage {action} {ip?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage security settings (block/unblock IPs, clear cache, view logs)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $ip = $this->argument('ip');

        switch ($action) {
            case 'block-ip':
                $this->blockIP($ip);
                break;
            case 'unblock-ip':
                $this->unblockIP($ip);
                break;
            case 'list-blocked':
                $this->listBlockedIPs();
                break;
            case 'clear-cache':
                $this->clearSecurityCache();
                break;
            case 'status':
                $this->showSecurityStatus();
                break;
            default:
                $this->error('Invalid action. Available actions: block-ip, unblock-ip, list-blocked, clear-cache, status');
                return 1;
        }

        return 0;
    }

    /**
     * Block an IP address
     */
    protected function blockIP($ip)
    {
        if (!$ip) {
            $this->error('IP address is required');
            return;
        }

        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            $this->error('Invalid IP address format');
            return;
        }

        $blockKey = 'ip_blocked:' . $ip;
        Cache::put($blockKey, now()->addDays(30), now()->addDays(30)); // Block for 30 days

        Log::warning('IP manually blocked via command', [
            'ip' => $ip,
            'admin' => 'system',
            'timestamp' => now()
        ]);

        $this->info("IP {$ip} has been blocked successfully");
    }

    /**
     * Unblock an IP address
     */
    protected function unblockIP($ip)
    {
        if (!$ip) {
            $this->error('IP address is required');
            return;
        }

        $blockKey = 'ip_blocked:' . $ip;
        $failedKey = 'failed_logins:' . $ip;
        
        Cache::forget($blockKey);
        Cache::forget($failedKey);

        Log::info('IP manually unblocked via command', [
            'ip' => $ip,
            'admin' => 'system',
            'timestamp' => now()
        ]);

        $this->info("IP {$ip} has been unblocked successfully");
    }

    /**
     * List blocked IPs from cache
     */
    protected function listBlockedIPs()
    {
        $this->info('Searching for blocked IPs in cache...');
        
        // This is a simplified version - in production, you might want to store blocked IPs in database
        $blockedFromConfig = config('security.ip_blocking.blocked_ips', []);
        
        if (!empty($blockedFromConfig)) {
            $this->info('Permanently blocked IPs from config:');
            foreach ($blockedFromConfig as $ip) {
                $this->line("- {$ip}");
            }
        } else {
            $this->info('No permanently blocked IPs found in config');
        }
        
        // Note: Temporarily blocked IPs from cache would need custom cache key enumeration
        $this->warn('Note: Temporarily blocked IPs from failed logins are not shown here');
    }

    /**
     * Clear security-related cache
     */
    protected function clearSecurityCache()
    {
        // Clear rate limiting cache
        Cache::flush();
        
        Log::info('Security cache cleared via command', [
            'admin' => 'system',
            'timestamp' => now()
        ]);

        $this->info('Security cache has been cleared successfully');
        $this->warn('This will also clear all other cached data');
    }

    /**
     * Show security status
     */
    protected function showSecurityStatus()
    {
        $this->info('=== Security Status ===');
        
        $this->table(['Setting', 'Value'], [
            ['IP Blocking Enabled', config('security.ip_blocking.enabled') ? 'Yes' : 'No'],
            ['HTTPS Forced', config('security.https.force_https') ? 'Yes' : 'No'],
            ['HSTS Enabled', config('security.https.hsts_enabled') ? 'Yes' : 'No'],
            ['Session Secure Cookies', config('security.session.secure_cookies') ? 'Yes' : 'No'],
            ['Max File Upload Size', config('security.file_upload.max_size') . ' bytes'],
            ['Environment', app()->environment()],
            ['Debug Mode', config('app.debug') ? 'On (Disable in production!)' : 'Off'],
        ]);

        if (app()->environment('production') && config('app.debug')) {
            $this->error('WARNING: Debug mode is enabled in production!');
        }
    }
}
