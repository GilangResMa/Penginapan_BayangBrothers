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
                'input' => $request->all(),
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
        $inputString = json_encode($input);
        
        $sqlPatterns = [
            '/(\bUNION\b.*\bSELECT\b)/i',
            '/(\bSELECT\b.*\bFROM\b)/i',
            '/(\bINSERT\b.*\bINTO\b)/i',
            '/(\bUPDATE\b.*\bSET\b)/i',
            '/(\bDELETE\b.*\bFROM\b)/i',
            '/(\bDROP\b.*\bTABLE\b)/i',
            '/(\bCREATE\b.*\bTABLE\b)/i',
            '/(\bALTER\b.*\bTABLE\b)/i',
            '/(\bTRUNCATE\b.*\bTABLE\b)/i',
            '/(\bEXEC\b|\bEXECUTE\b)/i',
            '/(;.*--)/i',
            '/(\bOR\b.*1=1)/i',
            '/(\bAND\b.*1=1)/i',
            '/(\'.*OR.*\'.*=.*\')/i',
            '/(\".*OR.*\".*=.*\")/i',
            '/(\bINFORMATION_SCHEMA\b)/i',
            '/(\bSYSTEM_USER\b)/i',
            '/(\bDATABASE\b\(\))/i',
            '/(\bVERSION\b\(\))/i',
            '/(\bCONCAT\b\()/i',
            '/(\bCHAR\b\()/i',
            '/(\bHEX\b\()/i',
            '/(\bASCII\b\()/i',
            '/(\bSUBSTRING\b\()/i',
            '/(\bLENGTH\b\()/i'
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
