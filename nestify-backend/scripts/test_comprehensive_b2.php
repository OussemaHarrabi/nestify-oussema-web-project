<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\ImageUploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

echo "🧪 COMPREHENSIVE BACKBLAZE B2 TEST\n";
echo "=====================================\n\n";

$allTestsPassed = true;
$testResults = [];

// Test 1: Basic Configuration
echo "1️⃣ Testing Configuration...\n";
try {
    $requiredEnv = [
        'B2_KEY_ID' => env('B2_KEY_ID'),
        'B2_APPLICATION_KEY' => env('B2_APPLICATION_KEY'),
        'B2_BUCKET_NAME' => env('B2_BUCKET_NAME'),
        'B2_ENDPOINT' => env('B2_ENDPOINT'),
        'B2_REGION' => env('B2_REGION'),
        'B2_URL' => env('B2_URL'),
    ];
    
    foreach ($requiredEnv as $key => $value) {
        if (empty($value)) {
            throw new \Exception("Missing environment variable: {$key}");
        }
    }
    
    echo "✅ Configuration: All environment variables present\n";
    $testResults['configuration'] = 'PASS';
} catch (\Exception $e) {
    echo "❌ Configuration: {$e->getMessage()}\n";
    $testResults['configuration'] = 'FAIL';
    $allTestsPassed = false;
}

// Test 2: ImageUploadService Initialization
echo "\n2️⃣ Testing ImageUploadService Initialization...\n";
try {
    $imageService = new ImageUploadService();
    echo "✅ ImageUploadService: Successfully initialized\n";
    $testResults['service_init'] = 'PASS';
} catch (\Exception $e) {
    echo "❌ ImageUploadService: {$e->getMessage()}\n";
    $testResults['service_init'] = 'FAIL';
    $allTestsPassed = false;
}

// Test 3: Folder Structure
echo "\n3️⃣ Testing Folder Structure...\n";
try {
    $folders = $imageService->getFolderStructure();
    if (empty($folders)) {
        throw new \Exception('Folder structure is empty');
    }
    
    echo "✅ Folder Structure: " . count($folders) . " categories found\n";
    foreach ($folders as $category => $subfolders) {
        echo "   - {$category}: " . implode(', ', $subfolders) . "\n";
    }
    $testResults['folder_structure'] = 'PASS';
} catch (\Exception $e) {
    echo "❌ Folder Structure: {$e->getMessage()}\n";
    $testResults['folder_structure'] = 'FAIL';
    $allTestsPassed = false;
}

// Test 4: URL Generation
echo "\n4️⃣ Testing URL Generation...\n";
try {
    $testPath = 'test/url-generation.jpg';
    $generatedUrl = $imageService->getUrl($testPath);
    $expectedBase = env('B2_URL');
    
    if (strpos($generatedUrl, $expectedBase) !== 0) {
        throw new \Exception("URL doesn't match expected base: {$generatedUrl}");
    }
    
    echo "✅ URL Generation: {$generatedUrl}\n";
    $testResults['url_generation'] = 'PASS';
} catch (\Exception $e) {
    echo "❌ URL Generation: {$e->getMessage()}\n";
    $testResults['url_generation'] = 'FAIL';
    $allTestsPassed = false;
}

// Test 5: File Upload (Text File)
echo "\n5️⃣ Testing File Upload (Text)...\n";
try {
    $tempFile = tempnam(sys_get_temp_dir(), 'test_upload_');
    $testContent = 'Hello Backblaze B2 from Nestify! - ' . now();
    file_put_contents($tempFile, $testContent);
    
    $uploadedFile = new UploadedFile(
        $tempFile,
        'test.txt',
        'text/plain',
        null,
        true
    );
    
    $result = $imageService->upload($uploadedFile, 'test');
    
    if (empty($result['path']) || empty($result['url'])) {
        throw new \Exception('Upload result is incomplete');
    }
    
    echo "✅ File Upload: Successfully uploaded to {$result['path']}\n";
    echo "   URL: {$result['url']}\n";
    $testResults['file_upload'] = 'PASS';
    
    // Store for cleanup
    $uploadedPath = $result['path'];
    
    unlink($tempFile);
} catch (\Exception $e) {
    echo "❌ File Upload: {$e->getMessage()}\n";
    $testResults['file_upload'] = 'FAIL';
    $allTestsPassed = false;
    if (file_exists($tempFile)) {
        unlink($tempFile);
    }
}

// Test 6: File Existence Check
echo "\n6️⃣ Testing File Existence...\n";
try {
    if (!isset($uploadedPath)) {
        throw new \Exception('No uploaded file to test');
    }
    
    $exists = $imageService->exists($uploadedPath);
    if (!$exists) {
        throw new \Exception('Uploaded file does not exist');
    }
    
    echo "✅ File Existence: File exists and is accessible\n";
    $testResults['file_existence'] = 'PASS';
} catch (\Exception $e) {
    echo "❌ File Existence: {$e->getMessage()}\n";
    $testResults['file_existence'] = 'FAIL';
    $allTestsPassed = false;
}

// Test 7: File Size Check
echo "\n7️⃣ Testing File Size...\n";
try {
    if (!isset($uploadedPath)) {
        throw new \Exception('No uploaded file to test');
    }
    
    $size = $imageService->size($uploadedPath);
    if ($size <= 0) {
        throw new \Exception('File size is invalid: ' . $size);
    }
    
    echo "✅ File Size: {$size} bytes\n";
    $testResults['file_size'] = 'PASS';
} catch (\Exception $e) {
    echo "❌ File Size: {$e->getMessage()}\n";
    $testResults['file_size'] = 'FAIL';
    $allTestsPassed = false;
}

// Test 8: Multiple File Upload
echo "\n8️⃣ Testing Multiple File Upload...\n";
try {
    $tempFiles = [];
    $uploadedFiles = [];
    
    // Create 3 test files
    for ($i = 1; $i <= 3; $i++) {
        $tempFile = tempnam(sys_get_temp_dir(), "multi_test_{$i}_");
        $content = "Multiple file test {$i} - " . now();
        file_put_contents($tempFile, $content);
        
        $uploadedFiles[] = new UploadedFile(
            $tempFile,
            "multi_test_{$i}.txt",
            'text/plain',
            null,
            true
        );
        
        $tempFiles[] = $tempFile;
    }
    
    $results = $imageService->uploadMultiple($uploadedFiles, 'test/multiple');
    
    if (count($results) !== 3) {
        throw new \Exception('Expected 3 files, got ' . count($results));
    }
    
    echo "✅ Multiple Upload: Successfully uploaded " . count($results) . " files\n";
    foreach ($results as $i => $result) {
        echo "   File " . ($i + 1) . ": {$result['path']}\n";
    }
    
    $testResults['multiple_upload'] = 'PASS';
    
    // Store for cleanup
    $multipleUploadedPaths = array_column($results, 'path');
    
    // Clean up temp files
    foreach ($tempFiles as $tempFile) {
        unlink($tempFile);
    }
    
} catch (\Exception $e) {
    echo "❌ Multiple Upload: {$e->getMessage()}\n";
    $testResults['multiple_upload'] = 'FAIL';
    $allTestsPassed = false;
    
    // Clean up temp files
    foreach ($tempFiles as $tempFile) {
        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
    }
}

// Test 9: PDF Upload
echo "\n9️⃣ Testing PDF Upload...\n";
try {
    $tempFile = tempnam(sys_get_temp_dir(), 'test_pdf_');
    $pdfContent = '%PDF-1.4 Test PDF Content for Backblaze B2';
    file_put_contents($tempFile, $pdfContent);
    
    $uploadedFile = new UploadedFile(
        $tempFile,
        'test.pdf',
        'application/pdf',
        null,
        true
    );
    
    $result = $imageService->uploadPDF($uploadedFile, 'test/documents');
    
    if (empty($result['path']) || empty($result['url'])) {
        throw new \Exception('PDF upload result is incomplete');
    }
    
    echo "✅ PDF Upload: Successfully uploaded to {$result['path']}\n";
    echo "   URL: {$result['url']}\n";
    $testResults['pdf_upload'] = 'PASS';
    
    // Store for cleanup
    $pdfUploadedPath = $result['path'];
    
    unlink($tempFile);
} catch (\Exception $e) {
    echo "❌ PDF Upload: {$e->getMessage()}\n";
    $testResults['pdf_upload'] = 'FAIL';
    $allTestsPassed = false;
    if (file_exists($tempFile)) {
        unlink($tempFile);
    }
}

// Test 10: URL Path Extraction
echo "\n🔟 Testing URL Path Extraction...\n";
try {
    $testUrl = env('B2_URL') . '/test/extraction/file.jpg';
    $extractedPath = $imageService->extractPathFromUrl($testUrl);
    
    if ($extractedPath !== 'test/extraction/file.jpg') {
        throw new \Exception("Path extraction failed. Expected 'test/extraction/file.jpg', got '{$extractedPath}'");
    }
    
    echo "✅ URL Extraction: '{$testUrl}' -> '{$extractedPath}'\n";
    $testResults['url_extraction'] = 'PASS';
} catch (\Exception $e) {
    echo "❌ URL Extraction: {$e->getMessage()}\n";
    $testResults['url_extraction'] = 'FAIL';
    $allTestsPassed = false;
}

// Test 11: File Deletion
echo "\n1️⃣1️⃣ Testing File Deletion...\n";
try {
    $deletedCount = 0;
    
    // Delete single uploaded file
    if (isset($uploadedPath)) {
        $deleted = $imageService->delete($uploadedPath);
        if ($deleted) {
            $deletedCount++;
            echo "✅ Single File Deletion: Successfully deleted {$uploadedPath}\n";
        } else {
            throw new \Exception('Failed to delete single file');
        }
    }
    
    // Delete multiple uploaded files
    if (isset($multipleUploadedPaths)) {
        foreach ($multipleUploadedPaths as $path) {
            $deleted = $imageService->delete($path);
            if ($deleted) {
                $deletedCount++;
            }
        }
        echo "✅ Multiple File Deletion: Successfully deleted " . count($multipleUploadedPaths) . " files\n";
    }
    
    // Delete PDF file
    if (isset($pdfUploadedPath)) {
        $deleted = $imageService->delete($pdfUploadedPath);
        if ($deleted) {
            $deletedCount++;
            echo "✅ PDF Deletion: Successfully deleted {$pdfUploadedPath}\n";
        } else {
            throw new \Exception('Failed to delete PDF file');
        }
    }
    
    echo "✅ File Deletion: Total {$deletedCount} files deleted\n";
    $testResults['file_deletion'] = 'PASS';
    
} catch (\Exception $e) {
    echo "❌ File Deletion: {$e->getMessage()}\n";
    $testResults['file_deletion'] = 'FAIL';
    $allTestsPassed = false;
}

// Test 12: Error Handling
echo "\n1️⃣2️⃣ Testing Error Handling...\n";
try {
    // Test deletion of non-existent file
    $deleted = $imageService->delete('non-existent-file.txt');
    if ($deleted !== false) {
        throw new \Exception('Should return false for non-existent file');
    }
    
    // Test existence check for non-existent file
    $exists = $imageService->exists('non-existent-file.txt');
    if ($exists !== false) {
        throw new \Exception('Should return false for non-existent file');
    }
    
    echo "✅ Error Handling: Properly handles non-existent files\n";
    $testResults['error_handling'] = 'PASS';
    
} catch (\Exception $e) {
    echo "❌ Error Handling: {$e->getMessage()}\n";
    $testResults['error_handling'] = 'FAIL';
    $allTestsPassed = false;
}

// Final Results
echo "\n" . str_repeat("=", 50) . "\n";
echo "📊 TEST RESULTS SUMMARY\n";
echo str_repeat("=", 50) . "\n";

$passedTests = 0;
$totalTests = count($testResults);

foreach ($testResults as $test => $result) {
    $status = $result === 'PASS' ? '✅' : '❌';
    echo "{$status} " . ucwords(str_replace('_', ' ', $test)) . "\n";
    if ($result === 'PASS') {
        $passedTests++;
    }
}

echo "\n";
echo "📈 Overall Score: {$passedTests}/{$totalTests} tests passed\n";

if ($allTestsPassed) {
    echo "\n🎉 ALL TESTS PASSED! 🎉\n";
    echo "✅ Your Backblaze B2 integration is fully functional!\n";
    echo "✅ Ready for production use!\n";
    echo "\n🚀 Next Steps:\n";
    echo "   1. Integrate ImageUploadService into your controllers\n";
    echo "   2. Update PropertyController to use B2 for image uploads\n";
    echo "   3. Update ProjectController to use B2 for file uploads\n";
    echo "   4. Test with real image files\n";
    echo "   5. Set up monitoring and alerts in Backblaze dashboard\n";
} else {
    echo "\n⚠️  SOME TESTS FAILED ⚠️\n";
    echo "Please review the failed tests above and fix the issues.\n";
}

echo "\n🎯 Test completed at " . now() . "\n";

