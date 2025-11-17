<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageUploadService;

class TestBackblazeConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backblaze:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Backblaze B2 connection and upload functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing Backblaze B2 Connection...');
        $this->newLine();

        try {
            // Test 1: Check configuration
            $this->info('1️⃣ Testing configuration...');
            $this->checkConfiguration();
            $this->info('✅ Configuration is valid');
            $this->newLine();

            // Test 2: Test connection
            $this->info('2️⃣ Testing connection...');
            $this->testConnection();
            $this->info('✅ Connection successful');
            $this->newLine();

            // Test 3: Test file operations
            $this->info('3️⃣ Testing file operations...');
            $this->testFileOperations();
            $this->info('✅ File operations successful');
            $this->newLine();

            // Test 4: Test ImageUploadService
            $this->info('4️⃣ Testing ImageUploadService...');
            $this->testImageUploadService();
            $this->info('✅ ImageUploadService working');
            $this->newLine();

            $this->info('🎉 All Backblaze B2 tests passed successfully!');
            $this->newLine();
            $this->info('Your Backblaze B2 integration is ready to use!');

        } catch (\Exception $e) {
            $this->error('❌ Test failed: ' . $e->getMessage());
            $this->newLine();
            $this->error('Please check your configuration and try again.');
            return 1;
        }

        return 0;
    }

    private function checkConfiguration()
    {
        $requiredEnvVars = [
            'B2_KEY_ID',
            'B2_APPLICATION_KEY',
            'B2_BUCKET_NAME',
            'B2_ENDPOINT',
            'B2_REGION',
            'B2_URL'
        ];

        foreach ($requiredEnvVars as $var) {
            if (!env($var)) {
                throw new \Exception("Missing required environment variable: {$var}");
            }
        }

        // Check if B2 disk is configured
        $disk = config('filesystems.disks.b2');
        if (!$disk) {
            throw new \Exception('B2 disk not configured in filesystems.php');
        }
    }

    private function testConnection()
    {
        $disk = Storage::disk('b2');
        
        // Test if we can list files (this tests the connection)
        try {
            $files = $disk->files('');
            // This should not throw an exception even if empty
        } catch (\Exception $e) {
            throw new \Exception('Cannot connect to Backblaze B2: ' . $e->getMessage());
        }
    }

    private function testFileOperations()
    {
        $imageService = new ImageUploadService();
        
        // Create a temporary text file for testing
        $tempFile = tempnam(sys_get_temp_dir(), 'test_upload_');
        file_put_contents($tempFile, 'Hello Backblaze B2 from Nestify! - ' . now());
        
        // Create a mock UploadedFile
        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $tempFile,
            'test.txt',
            'text/plain',
            null,
            true
        );
        
        try {
            // Test upload using ImageUploadService
            $result = $imageService->upload($uploadedFile, 'test');
            
            $this->line("   📁 Test file uploaded successfully");
            $this->line("   🔗 Generated URL: {$result['url']}");
            $this->line("   📏 File size: " . $imageService->size($result['path']) . " bytes");
            
            // Test file existence
            if (!$imageService->exists($result['path'])) {
                throw new \Exception('File existence check failed');
            }
            
            // Clean up - delete test file
            $imageService->delete($result['path']);
            $this->line("   🗑️  Test file deleted successfully");
            
            // Clean up temp file
            unlink($tempFile);

        } catch (\Exception $e) {
            // Try to clean up if something went wrong
            try {
                if (isset($result)) {
                    $imageService->delete($result['path']);
                }
            } catch (\Exception $cleanupException) {
                // Ignore cleanup errors
            }
            
            // Clean up temp file
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
            
            throw $e;
        }
    }

    private function testImageUploadService()
    {
        $imageService = new ImageUploadService();

        // Test folder structure
        $folders = $imageService->getFolderStructure();
        if (empty($folders)) {
            throw new \Exception('Folder structure is empty');
        }

        // Test URL extraction
        $testUrl = 'https://f003.backblazeb2.com/file/neuf-images/test/file.jpg';
        $extractedPath = $imageService->extractPathFromUrl($testUrl);
        if ($extractedPath !== 'test/file.jpg') {
            throw new \Exception('URL path extraction failed');
        }

        $this->line("   📂 Folder structure: " . count($folders) . " categories");
        $this->line("   🔗 URL extraction working");
    }
}