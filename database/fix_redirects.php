<?php
/**
 * Script to fix all hardcoded redirect('/public/...') calls in PHP files
 */

$publicDir = __DIR__ . '/../public';

function fixRedirects($file) {
    $content = file_get_contents($file);
    $original = $content;
    
    // Replace redirect('/public/...') with redirect(url('...'))
    $content = preg_replace(
        "/redirect\('\/public\/([^']+)'\)/",
        "redirect(url('$1'))",
        $content
    );
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        return true;
    }
    
    return false;
}

// Find all PHP files
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($publicDir)
);

$updated = 0;
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        if (fixRedirects($file->getPathname())) {
            echo "Updated: " . $file->getPathname() . "\n";
            $updated++;
        }
    }
}

echo "\nTotal files updated: $updated\n";
