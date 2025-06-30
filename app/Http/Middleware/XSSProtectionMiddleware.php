<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class XSSProtectionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Clean input data
        $input = $request->all();
        $cleanedInput = $this->cleanInput($input);
        $request->merge($cleanedInput);
        
        // Check for potential XSS attacks
        if ($this->containsSuspiciousContent($input)) {
            Log::warning('Potential XSS attack detected', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'input' => $input,
                'user_id' => $request->user() ? $request->user()->id : null
            ]);
            
            return response()->json([
                'error' => 'Invalid input detected. Request blocked for security reasons.'
            ], 400);
        }
        
        return $next($request);
    }
    
    /**
     * Clean input data to prevent XSS
     */
    protected function cleanInput(array $input): array
    {
        array_walk_recursive($input, function (&$value) {
            if (is_string($value)) {
                // Remove potentially dangerous tags and attributes
                $value = $this->sanitizeString($value);
            }
        });
        
        return $input;
    }
    
    /**
     * Sanitize string input
     */
    protected function sanitizeString(string $value): string
    {
        // Remove null bytes
        $value = str_replace(chr(0), '', $value);
        
        // Remove dangerous HTML tags
        $dangerousTags = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/mi',
            '/<object\b[^<]*(?:(?!<\/object>)<[^<]*)*<\/object>/mi',
            '/<embed\b[^<]*(?:(?!<\/embed>)<[^<]*)*<\/embed>/mi',
            '/<applet\b[^<]*(?:(?!<\/applet>)<[^<]*)*<\/applet>/mi',
            '/<form\b[^<]*(?:(?!<\/form>)<[^<]*)*<\/form>/mi',
            '/<meta\b[^>]*>/mi',
            '/<link\b[^>]*>/mi',
            '/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/mi'
        ];
        
        $value = preg_replace($dangerousTags, '', $value);
        
        // Remove dangerous attributes
        $dangerousAttributes = [
            '/\son\w+\s*=\s*["\'][^"\']*["\']/mi', // onload, onclick, etc.
            '/\sjavascript\s*:/mi',
            '/\svbscript\s*:/mi',
            '/\sdata\s*:/mi'
        ];
        
        $value = preg_replace($dangerousAttributes, '', $value);
        
        return $value;
    }
    
    /**
     * Check if input contains suspicious content
     */
    protected function containsSuspiciousContent(array $input): bool
    {
        $inputString = json_encode($input);
        
        // Patterns that might indicate XSS attempts
        $suspiciousPatterns = [
            '/<script/i',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload=/i',
            '/onclick=/i',
            '/onerror=/i',
            '/onmouseover=/i',
            '/onfocus=/i',
            '/onblur=/i',
            '/alert\s*\(/i',
            '/confirm\s*\(/i',
            '/prompt\s*\(/i',
            '/document\.cookie/i',
            '/document\.write/i',
            '/window\.location/i',
            '/eval\s*\(/i',
            '/setTimeout\s*\(/i',
            '/setInterval\s*\(/i',
            '/<iframe/i',
            '/<object/i',
            '/<embed/i',
            '/<applet/i',
            '/<meta/i',
            '/<link/i',
            '/style\s*=.*expression\s*\(/i',
            '/style\s*=.*javascript:/i',
            '/data:\s*text\/html/i',
            '/&#x/i', // Hex encoded characters
            '/&\#\d+;/i' // Decimal encoded characters
        ];
        
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $inputString)) {
                return true;
            }
        }
        
        return false;
    }
}
