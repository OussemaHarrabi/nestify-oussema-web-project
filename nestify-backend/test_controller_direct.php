<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\Api\PublicController;
use Illuminate\Http\Request;

echo "🧪 Testing Controller Method Directly\n";
echo "═══════════════════════════════════════\n\n";

// Create controller instance
$controller = new PublicController();

echo "Test 1: projectById(5)\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

try {
    $response = $controller->projectById(5);
    $data = $response->getData();
    
    echo "✅ SUCCESS!\n";
    echo "Project: {$data->name}\n";
    echo "Slug: {$data->slug}\n";
    echo "Published: " . ($data->is_published ? 'Yes' : 'No') . "\n";
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}

echo "\n\nTest 2: projectBySlug('residence-marina-bay')\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

try {
    $response = $controller->projectBySlug('residence-marina-bay');
    $data = $response->getData();
    
    echo "✅ SUCCESS!\n";
    echo "Project: {$data->name}\n";
    echo "ID: {$data->id}\n";
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}

echo "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "CONCLUSION:\n";
echo "If both tests pass, the controller works fine.\n";
echo "The issue is the Laravel server not picking up route changes.\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
