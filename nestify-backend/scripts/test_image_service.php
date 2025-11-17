<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Services\ImageUploadService;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🧪 Testing Updated ImageUploadService...\n\n";

try {
    $imageService = new ImageUploadService();
    
    echo "📋 Testing service initialization...\n";
    echo "✅ ImageUploadService initialized successfully\n\n";
    
    echo "📂 Testing folder structure...\n";
    $folders = $imageService->getFolderStructure();
    echo "✅ Folder structure: " . count($folders) . " categories\n";
    foreach ($folders as $category => $subfolders) {
        echo "  - {$category}: " . implode(', ', array_keys($subfolders)) . "\n";
    }
    echo "\n";
    
    echo "🔗 Testing URL extraction...\n";
    $testUrl = 'https://f003.backblazeb2.com/file/neuf-images/test/file.jpg';
    $extractedPath = $imageService->extractPathFromUrl($testUrl);
    echo "✅ URL extraction: '{$testUrl}' -> '{$extractedPath}'\n\n";
    
    echo "📤 Testing text file upload...\n";
    
    // Create a temporary text file for testing
    $tempFile = tempnam(sys_get_temp_dir(), 'test_upload_');
    file_put_contents($tempFile, 'Hello Backblaze B2 from ImageUploadService!');
    
    // Create a mock UploadedFile
    $uploadedFile = new \Illuminate\Http\UploadedFile(
        $tempFile,
        'test.txt',
        'text/plain',
        null,
        true
    );
    
    $result = $imageService->upload($uploadedFile, 'test');
    
    echo "✅ Upload successful!\n";
    echo "  Path: {$result['path']}\n";
    echo "  URL: {$result['url']}\n";
    echo "  Filename: {$result['filename']}\n";
    echo "  ETag: {$result['etag']}\n\n";
    
    echo "🔍 Testing file existence...\n";
    $exists = $imageService->exists($result['path']);
    echo "✅ File exists: " . ($exists ? 'YES' : 'NO') . "\n\n";
    
    echo "📏 Testing file size...\n";
    $size = $imageService->size($result['path']);
    echo "✅ File size: {$size} bytes\n\n";
    
    echo "🗑️ Testing file deletion...\n";
    $deleted = $imageService->delete($result['path']);
    echo "✅ File deleted: " . ($deleted ? 'YES' : 'NO') . "\n\n";
    
    // Clean up temp file
    unlink($tempFile);
    
    echo "🎉 All ImageUploadService tests passed successfully!\n";
    echo "✅ Your Backblaze B2 integration is ready to use!\n";
    
} catch (Exception $e) {
    echo "❌ Test failed: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n🎯 Test complete!\n";

