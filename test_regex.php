<?php

// Test script untuk memvalidasi regex patterns di security middleware
// Jalankan dengan: php test_regex.php

echo "Testing Security Middleware Regex Patterns\n";
echo "==========================================\n\n";

// Test XSS patterns
echo "1. Testing XSS Protection Patterns:\n";
$xssPatterns = [
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
    '/&#x/i',
    '/&\#\d+;/i'
];

$xssTestString = '<script>alert("test")</script>';
$validCount = 0;

foreach ($xssPatterns as $pattern) {
    try {
        $result = preg_match($pattern, $xssTestString);
        $validCount++;
        echo "✓ Pattern: {$pattern} - Valid\n";
    } catch (Exception $e) {
        echo "✗ Pattern: {$pattern} - Error: " . $e->getMessage() . "\n";
    }
}

echo "\nXSS Patterns Valid: {$validCount}/" . count($xssPatterns) . "\n\n";

// Test SQL Injection patterns
echo "2. Testing SQL Injection Patterns:\n";
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

$sqlTestString = "SELECT * FROM users WHERE id = 1 OR 1=1";
$validCount = 0;

foreach ($sqlPatterns as $pattern) {
    try {
        $result = preg_match($pattern, $sqlTestString);
        $validCount++;
        echo "✓ Pattern: {$pattern} - Valid\n";
    } catch (Exception $e) {
        echo "✗ Pattern: {$pattern} - Error: " . $e->getMessage() . "\n";
    }
}

echo "\nSQL Injection Patterns Valid: {$validCount}/" . count($sqlPatterns) . "\n\n";

// Test Directory Traversal patterns
echo "3. Testing Directory Traversal Patterns:\n";
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

$traversalTestString = "../../../etc/passwd";
$validCount = 0;

foreach ($traversalPatterns as $pattern) {
    try {
        $result = preg_match($pattern, $traversalTestString);
        $validCount++;
        echo "✓ Pattern: {$pattern} - Valid\n";
    } catch (Exception $e) {
        echo "✗ Pattern: {$pattern} - Error: " . $e->getMessage() . "\n";
    }
}

echo "\nDirectory Traversal Patterns Valid: {$validCount}/" . count($traversalPatterns) . "\n\n";

echo "==========================================\n";
echo "Regex Pattern Validation Complete!\n";
echo "All patterns should show 'Valid' for proper functionality.\n";
