<?php

require_once __DIR__ . '/vendor/autoload.php';

use Aws\S3\S3Client;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Testing Backblaze B2 Upload (No ACL)...\n\n";

try {
    $s3Client = new S3Client([
        'version' => 'latest',
        'region' => env('B2_REGION', 'eu-central-003'),
        'endpoint' => env('B2_ENDPOINT'),
        'credentials' => [
            'key' => env('B2_KEY_ID'),
            'secret' => env('B2_APPLICATION_KEY'),
        ],
        'use_path_style_endpoint' => true,
    ]);

    echo "📤 Testing upload without ACL...\n";
    $testContent = 'Hello Backblaze B2 - No ACL!';
    
    try {
        $result = $s3Client->putObject([
            'Bucket' => env('B2_BUCKET_NAME'),
            'Key' => 'test-no-acl.txt',
            'Body' => $testContent
        ]);
        
        echo "✅ Upload successful!\n";
        echo "  ETag: " . $result['ETag'] . "\n";
        
        // Try to get the object
        echo "📖 Testing file retrieval...\n";
        $object = $s3Client->getObject([
            'Bucket' => env('B2_BUCKET_NAME'),
            'Key' => 'test-no-acl.txt'
        ]);
        
        $retrievedContent = $object['Body']->getContents();
        echo "✅ File retrieved successfully!\n";
        echo "  Content: " . $retrievedContent . "\n";
        
        // Test URL generation
        echo "🔗 Testing URL generation...\n";
        $url = $s3Client->getObjectUrl(env('B2_BUCKET_NAME'), 'test-no-acl.txt');
        echo "  URL: " . $url . "\n";
        
        // Clean up
        echo "🗑️ Cleaning up test file...\n";
        $s3Client->deleteObject([
            'Bucket' => env('B2_BUCKET_NAME'),
            'Key' => 'test-no-acl.txt'
        ]);
        echo "✅ Test file deleted\n";
        
        echo "\n🎉 SUCCESS! Backblaze B2 is working correctly!\n";
        
    } catch (Exception $e) {
        echo "❌ Upload test failed: " . $e->getMessage() . "\n";
        
        // Check if it's a permissions issue
        if (strpos($e->getMessage(), 'AccessDenied') !== false) {
            echo "\n💡 This looks like a permissions issue.\n";
            echo "Please check:\n";
            echo "1. The bucket 'neuf-images' is set to Public\n";
            echo "2. Your API key has read/write permissions\n";
            echo "3. The bucket exists and is accessible\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Configuration error: " . $e->getMessage() . "\n";
}

echo "\n🎯 Test complete!\n";

