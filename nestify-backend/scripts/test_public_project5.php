<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Testing Public Project#5 Endpoint\n";
echo "═══════════════════════════════════════\n\n";

$url = 'http://127.0.0.1:8000/api/projects/5';

echo "URL: {$url}\n\n";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
curl_setopt($ch, CURLOPT_VERBOSE, true);
$verbose = fopen('php://temp', 'w+');
curl_setopt($ch, CURLOPT_STDERR, $verbose);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

rewind($verbose);
$verboseLog = stream_get_contents($verbose);

echo "HTTP CODE: {$httpCode}\n";
echo "RESPONSE: {$response}\n\n";

if ($httpCode === 404) {
    echo "❌ Still getting 404\n\n";
    echo "Checking project in database...\n";
    
    $project = \App\Models\Project::find(5);
    if ($project) {
        echo "✅ Project exists in DB\n";
        echo "   ID: {$project->id}\n";
        echo "   Title: {$project->title}\n";
        echo "   Published: " . ($project->is_published ? 'Yes' : 'No') . "\n";
        echo "   Published At: {$project->published_at}\n\n";
        
        // Test route directly
        echo "Testing route matching...\n";
        $router = app('router');
        $request = \Illuminate\Http\Request::create('/api/projects/5', 'GET');
        $request->headers->set('Accept', 'application/json');
        
        try {
            $route = $router->getRoutes()->match($request);
            echo "✅ Route matched: " . $route->uri() . "\n";
            echo "   Controller: " . $route->getActionName() . "\n";
        } catch (\Exception $e) {
            echo "❌ Route match failed: " . $e->getMessage() . "\n";
        }
    } else {
        echo "❌ Project #5 not found in database\n";
    }
}

curl_close($ch);
