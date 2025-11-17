<?php

require_once __DIR__ . '/vendor/autoload.php';

use Aws\S3\S3Client;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Diagnosing Backblaze B2 Configuration...\n\n";

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

    echo "📋 Configuration:\n";
    echo "  Key ID: " . env('B2_KEY_ID') . "\n";
    echo "  Bucket: " . env('B2_BUCKET_NAME') . "\n";
    echo "  Endpoint: " . env('B2_ENDPOINT') . "\n";
    echo "  Region: " . env('B2_REGION') . "\n";
    echo "  URL: " . env('B2_URL') . "\n\n";

    echo "🔍 Testing bucket access...\n";
    
    // Test bucket existence
    try {
        $result = $s3Client->headBucket(['Bucket' => env('B2_BUCKET_NAME')]);
        echo "✅ Bucket exists and is accessible\n";
    } catch (Exception $e) {
        echo "❌ Bucket access failed: " . $e->getMessage() . "\n";
        
        // Try to list buckets to see what's available
        echo "\n🔍 Listing available buckets...\n";
        try {
            $buckets = $s3Client->listBuckets();
            echo "Available buckets:\n";
            foreach ($buckets['Buckets'] as $bucket) {
                echo "  - " . $bucket['Name'] . " (created: " . $bucket['CreationDate'] . ")\n";
            }
        } catch (Exception $listError) {
            echo "❌ Cannot list buckets: " . $listError->getMessage() . "\n";
        }
    }

    // Test simple upload
    echo "\n📤 Testing simple upload...\n";
    try {
        $testContent = 'Hello Backblaze B2!';
        $result = $s3Client->putObject([
            'Bucket' => env('B2_BUCKET_NAME'),
            'Key' => 'test-diagnostic.txt',
            'Body' => $testContent,
            'ACL' => 'public-read'
        ]);
        
        echo "✅ Upload successful!\n";
        echo "  ETag: " . $result['ETag'] . "\n";
        
        // Try to get the object
        echo "📖 Testing file retrieval...\n";
        $object = $s3Client->getObject([
            'Bucket' => env('B2_BUCKET_NAME'),
            'Key' => 'test-diagnostic.txt'
        ]);
        
        $retrievedContent = $object['Body']->getContents();
        echo "✅ File retrieved successfully!\n";
        echo "  Content: " . $retrievedContent . "\n";
        
        // Clean up
        echo "🗑️ Cleaning up test file...\n";
        $s3Client->deleteObject([
            'Bucket' => env('B2_BUCKET_NAME'),
            'Key' => 'test-diagnostic.txt'
        ]);
        echo "✅ Test file deleted\n";
        
    } catch (Exception $e) {
        echo "❌ Upload test failed: " . $e->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "❌ Configuration error: " . $e->getMessage() . "\n";
}

echo "\n🎯 Diagnosis complete!\n";

