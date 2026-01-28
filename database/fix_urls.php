<?php
/**
 * Script to fix all hardcoded /public/ URLs in blade templates
 */

$viewsDir = __DIR__ . '/../views';

// Patterns to replace
$replacements = [
    // Doctors
    '"/public/doctors/' => '"{{ url(\'doctors/',
    '`/public/doctors/' => '`{{ url(\'doctors/',
    
    // Patients  
    '"/public/patients/' => '"{{ url(\'patients/',
    '`/public/patients/' => '`{{ url(\'patients/',
    
    // Appointments
    '"/public/appointments/' => '"{{ url(\'appointments/',
    '`/public/appointments/' => '`{{ url(\'appointments/',
    
    // Ajax
    '`/public/ajax/' => '`{{ url(\'ajax/',
    '"/public/ajax/' => '"{{ url(\'ajax/',
    
    // Close the url() calls properly
    '.php"' => '.php\') }}"',
    '.php`' => '.php\') }}`',
];

// Additional specific replacements for complex cases
$specificReplacements = [
    // Form actions with conditionals
    "{{ isset(\$patient) ? '/public/patients/edit.php?id=' . \$patient['id'] : '/public/patients/create.php' }}" =>
    "{{ isset(\$patient) ? url('patients/edit.php?id=' . \$patient['id']) : url('patients/create.php') }}",
    
    "{{ isset(\$doctor) ? '/public/doctors/edit.php?id=' . \$doctor['id'] : '/public/doctors/create.php' }}" =>
    "{{ isset(\$doctor) ? url('doctors/edit.php?id=' . \$doctor['id']) : url('doctors/create.php') }}",
    
    "{{ isset(\$appointment) ? '/public/appointments/edit.php?id=' . \$appointment['id'] : '/public/appointments/create.php' }}" =>
    "{{ isset(\$appointment) ? url('appointments/edit.php?id=' . \$appointment['id']) : url('appointments/create.php') }}",
    
    // Search form action
    'action="/public/appointments/search.php"' => 'action="{{ url(\'appointments/search.php\') }}"',
];

function processFile($file, $replacements, $specificReplacements) {
    $content = file_get_contents($file);
    $original = $content;
    
    // Apply specific replacements first
    foreach ($specificReplacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    
    // Apply general replacements
    foreach ($replacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    
    // Fix any double url() calls that might have been created
    $content = preg_replace('/url\(\'([^\']+)\'\) }}\'\) }}/', 'url(\'$1\') }}', $content);
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        return true;
    }
    
    return false;
}

// Find all blade files
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($viewsDir)
);

$updated = 0;
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php' && strpos($file->getFilename(), '.blade.php') !== false) {
        if (processFile($file->getPathname(), $replacements, $specificReplacements)) {
            echo "Updated: " . $file->getPathname() . "\n";
            $updated++;
        }
    }
}

echo "\nTotal files updated: $updated\n";
