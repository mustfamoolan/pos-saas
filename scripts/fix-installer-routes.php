<?php

/**
 * Fix duplicate route name conflict in LaravelInstaller
 * This script runs automatically after composer install/update
 */

$routesFile = __DIR__ . '/../vendor/safiull/laravel-installer/src/Routes/web.php';

if (!file_exists($routesFile)) {
    // File doesn't exist yet, skip
    exit(0);
}

$content = file_get_contents($routesFile);

// Check if the fix is already applied
if (strpos($content, "'as' => 'LaravelEnvato::'") !== false) {
    // Fix already applied
    exit(0);
}

// Fix the route name conflict
// We need to find the second Route::group with 'envato' prefix that contains 'purchase-code/verify/process'
$lines = explode("\n", $content);
$fixed = false;
$envatoGroupCount = 0;

for ($i = 0; $i < count($lines); $i++) {
    // Check if this is a Route::group with 'envato' prefix
    if (strpos($lines[$i], "Route::group(['prefix' => 'envato'") !== false || 
        strpos($lines[$i], 'Route::group(["prefix" => "envato"') !== false) {
        $envatoGroupCount++;
        
        // Check the next 10 lines to see if this group contains 'purchase-code/verify/process'
        $nextLines = array_slice($lines, $i, 10);
        $nextContent = implode("\n", $nextLines);
        
        // If this is the second envato group and contains 'purchase-code/verify/process', fix it
        if ($envatoGroupCount === 2 && strpos($nextContent, 'purchase-code/verify/process') !== false) {
            // Replace LaravelInstaller:: or LaravelVerifier:: with LaravelEnvato::
            $originalLine = $lines[$i];
            $lines[$i] = str_replace("'as' => 'LaravelInstaller::'", "'as' => 'LaravelEnvato::'", $lines[$i]);
            $lines[$i] = str_replace("'as' => 'LaravelVerifier::'", "'as' => 'LaravelEnvato::'", $lines[$i]);
            
            // Also handle double quotes
            $lines[$i] = str_replace('"as" => "LaravelInstaller::"', '"as" => "LaravelEnvato::"', $lines[$i]);
            $lines[$i] = str_replace('"as" => "LaravelVerifier::"', '"as" => "LaravelEnvato::"', $lines[$i]);
            
            if ($lines[$i] !== $originalLine) {
                $fixed = true;
                break;
            }
        }
    }
}

if ($fixed) {
    file_put_contents($routesFile, implode("\n", $lines));
    echo "✓ Fixed duplicate route name conflict in LaravelInstaller\n";
}

