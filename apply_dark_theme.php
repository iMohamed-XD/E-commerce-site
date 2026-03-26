<?php

$files = [
    'resources/views/layouts/navigation.blade.php',
];

$replacements = [
    'bg-white' => 'bg-gray-800 border-gray-700',
    'bg-gray-50' => 'bg-gray-900',
    'bg-gray-100' => 'bg-gray-800',
    'text-gray-900' => 'text-gray-100',
    'text-gray-800' => 'text-gray-200',
    'text-gray-700' => 'text-gray-300',
    'text-gray-600' => 'text-gray-400',
    'text-gray-500' => 'text-gray-400',
    'border-gray-200' => 'border-gray-700',
    'border-gray-300' => 'border-gray-600',
    'border-gray-100' => 'border-gray-700',
    'divide-gray-200' => 'divide-gray-700',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace(array_keys($replacements), array_values($replacements), $content);
        file_put_contents($file, $content);
        echo "Updated: $file\n";
    }
}
echo "Dark theme styling applied successfully.\n";
