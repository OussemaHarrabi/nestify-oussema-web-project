<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Storage;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🧪 Testing Backblaze B2 Upload...\n";

try {
    $disk = Storage::disk('b2');
    
    // Test simple upload
    $testContent = 'Hello Backblaze B2!';
    $testPath = 'test-simple.txt';
    
    echo "📤 Uploading test file...\n";
    $result = $disk->put($testPath, $testContent);
    echo "Upload result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
    
    // Check if file exists
    echo "🔍 Checking if file exists...\n";
    $exists = $disk->exists($testPath);
    echo "File exists: " . ($exists ? 'YES' : 'NO') . "\n";
    
    if ($exists) {
        // Get file content
        echo "📖 Reading file content...\n";
        $content = $disk->get($testPath);
        echo "Content: " . $content . "\n";
        
        // Get URL
        echo "🔗 Getting file URL...\n";
        $url = $disk->url($testPath);
        echo "URL: " . $url . "\n";
        
        // Delete file
        echo "🗑️ Deleting test file...\n";
        $deleted = $disk->delete($testPath);
        echo "Delete result: " . ($deleted ? 'SUCCESS' : 'FAILED') . "\n";
    }
    
    echo "✅ Test completed successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
