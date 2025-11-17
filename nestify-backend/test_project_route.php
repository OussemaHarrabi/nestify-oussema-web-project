<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$url = 'http://127.0.0.1:8000/api/projects/5';

echo "Testing: {$url}\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
curl_setopt($ch, CURLOPT_VERBOSE, true);

$response = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "\nHTTP Code: {$code}\n";
echo "Response: {$response}\n";

// Also test by slug
$project = \App\Models\Project::find(5);
if ($project) {
    echo "\n\nProject exists:\n";
    echo "  ID: {$project->id}\n";
    echo "  Name: {$project->name}\n";
    echo "  Slug: {$project->slug}\n";
    echo "  Published: " . ($project->is_published ? 'Yes' : 'No') . "\n";
    
    echo "\n\nTesting by slug: /api/projects/{$project->slug}\n";
    $url2 = "http://127.0.0.1:8000/api/projects/{$project->slug}";
    
    $ch2 = curl_init($url2);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch2, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    $response2 = curl_exec($ch2);
    $code2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
    curl_close($ch2);
    
    echo "HTTP Code: {$code2}\n";
    if ($code2 === 200) {
        echo "✅ Works by slug!\n";
    } else {
        echo "Response: {$response2}\n";
    }
}
