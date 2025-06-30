<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class InputValidationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Debug logging for owner admin routes
        if (strpos($request->path(), 'owner') !== false) {
            Log::info('Owner route accessed', [
                'path' => $request->path(),
                'method' => $request->method(),
                'is_owner_admin_route' => $this->isOwnerAdminRoute($request),
                'is_legitimate_form' => $this->isLegitimateFormSubmission($request),
            ]);
        }

        // Skip validation for owner admin management routes to avoid false positives
        if ($this->isOwnerAdminRoute($request)) {
            Log::info('Skipping validation for owner admin route: ' . $request->path());
            return $next($request);
        }

        // Skip validation for specific form fields that might trigger false positives
        if ($this->isLegitimateFormSubmission($request)) {
            Log::info('Skipping validation for legitimate form submission: ' . $request->path());
            return $next($request);
        }

        // Validate file uploads
        if ($request->hasFile('image') || $request->hasFile('photo') || $request->hasFile('file')) {
            $this->validateFileUploads($request);
        }
        
        // Check for SQL injection patterns
        if ($this->containsSQLInjection($request->all())) {
            Log::warning('Potential SQL injection detected', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'path' => $request->path(),
                'method' => $request->method(),
                'input' => $request->except(['password', 'password_confirmation']), // Don't log passwords
                'user_id' => $request->user() ? $request->user()->id : null
            ]);
            
            return response()->json([
                'error' => 'Invalid input detected. Request blocked for security reasons.'
            ], 400);
        }
        
        // Check for directory traversal attempts
        if ($this->containsDirectoryTraversal($request->all())) {
            Log::warning('Directory traversal attempt detected', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'input' => $request->all()
            ]);
            
            return response()->json([
                'error' => 'Invalid file path detected.'
            ], 400);
        }
        
        return $next($request);
    }

    /**
     * Check if this is an owner admin management route
     */
    protected function isOwnerAdminRoute(Request $request): bool
    {
        $ownerAdminRoutes = [
            'owner/admin',
            'owner/admin/create',
            'owner/admins'
        ];

        $path = $request->path();

        foreach ($ownerAdminRoutes as $route) {
            if (strpos($path, $route) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if this is a legitimate form submission that might trigger false positives
     */
    protected function isLegitimateFormSubmission(Request $request): bool
    {
        // If this is a POST/PUT request with password fields, likely a legitimate form
        if (
            in_array($request->method(), ['POST', 'PUT', 'PATCH']) &&
            ($request->has('password') || $request->has('password_confirmation'))
        ) {
            return true;
        }

        // If this is an authenticated user making a request to admin/owner routes
        if ($request->user() && (strpos($request->path(), 'admin') !== false || strpos($request->path(), 'owner') !== false)) {
            return true;
        }

        return false;
    }

    /**
     * Validate file uploads
     */
    protected function validateFileUploads(Request $request): void
    {
        $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf'
        ];
        
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        
        foreach ($request->allFiles() as $key => $files) {
            if (!is_array($files)) {
                $files = [$files];
            }
            
            foreach ($files as $file) {
                // Check file size
                if ($file->getSize() > $maxFileSize) {
                    abort(413, 'File too large. Maximum size allowed is 5MB.');
                }
                
                // Check MIME type
                if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
                    abort(415, 'File type not allowed. Only images and PDF files are permitted.');
                }
                
                // Check for double extensions
                $filename = $file->getClientOriginalName();
                if (preg_match('/\.[^.]+\.[^.]+$/', $filename)) {
                    abort(400, 'Invalid filename. Double extensions are not allowed.');
                }
                
                // Check for executable extensions
                $dangerousExtensions = ['php', 'exe', 'bat', 'sh', 'cmd', 'scr', 'pif', 'jar'];
                $extension = strtolower($file->getClientOriginalExtension());
                if (in_array($extension, $dangerousExtensions)) {
                    abort(400, 'File type not allowed for security reasons.');
                }
            }
        }
    }
    
    /**
     * Check for SQL injection patterns
     */
    protected function containsSQLInjection(array $input): bool
    {
        // Exclude password fields and other legitimate fields from SQL injection check
        $excludedFields = ['password', 'password_confirmation', '_token', '_method', 'status', 'name', 'email'];
        $filteredInput = array_filter($input, function ($key) use ($excludedFields) {
            return !in_array($key, $excludedFields);
        }, ARRAY_FILTER_USE_KEY);

        // If no fields to check after filtering, skip
        if (empty($filteredInput)) {
            return false;
        }

        $inputString = json_encode($filteredInput);

        // More specific SQL injection patterns that are less likely to trigger false positives
        $sqlPatterns = [
            '/(\bUNION\s+SELECT\b)/i',
            '/(\bSELECT\s+\*\s+FROM\b)/i',
            '/(\bINSERT\s+INTO\b)/i',
            '/(\bDROP\s+TABLE\b)/i',
            '/(\bDELETE\s+FROM\b)/i',
            '/(\b--\s*$)/m',
            '/(;\s*DROP\b)/i',
            '/(;\s*DELETE\b)/i',
            '/(\bOR\s+1\s*=\s*1)/i',
            '/(\bAND\s+1\s*=\s*1)/i',
            '/(\';\s*DROP\b)/i',
            '/(\bINFORMATION_SCHEMA\b)/i',
            '/(\bSYSTEM_USER\b)/i',
        ];
        
        foreach ($sqlPatterns as $pattern) {
            if (preg_match($pattern, $inputString)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check for directory traversal attempts
     */
    protected function containsDirectoryTraversal(array $input): bool
    {
        $inputString = json_encode($input);
        
        $traversalPatterns = [
            '/\.\.\//',
            '/\.\.\\\\/',
            '/\.\.%2F/i',
            '/\.\.%5C/i',
            '/%2E%2E%2F/i',
            '/%2E%2E%5C/i',
            '/\.\.\//i',
            '/\.\.\\\\/i',
            '/%252E%252E/i',
            '/file:\/\//i',
            '/php:\/\//i',
            '/data:\/\//i',
            '/expect:\/\//i',
            '/zip:\/\//i'
        ];
        
        foreach ($traversalPatterns as $pattern) {
            if (preg_match($pattern, $inputString)) {
                return true;
            }
        }
        
        return false;
    }
}
