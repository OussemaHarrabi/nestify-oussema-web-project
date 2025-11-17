<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Aws\S3\S3Client;

class ImageUploadService
{
    private $bucket;
    private $s3Client;
    private $imageManager;
    
    public function __construct()
    {
        $this->bucket = env('B2_BUCKET_NAME');
        
        // Initialize image manager only if GD is available
        if (extension_loaded('gd')) {
            $this->imageManager = new ImageManager(new Driver());
        }
        
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region' => env('B2_REGION', 'eu-central-003'),
            'endpoint' => env('B2_ENDPOINT'),
            'credentials' => [
                'key' => env('B2_KEY_ID'),
                'secret' => env('B2_APPLICATION_KEY'),
            ],
            'use_path_style_endpoint' => true,
        ]);
    }
    
    /**
     * Upload and optimize image
     */
    public function upload(UploadedFile $file, string $folder = 'properties'): array
    {
        // Generate unique filename
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $path = "{$folder}/{$filename}";
        
        // Optimize image before upload (resize if too large)
        $optimized = $this->optimizeImage($file);
        
        // Upload to B2 using S3 client
        $result = $this->s3Client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $path,
            'Body' => $optimized,
            'ContentType' => $file->getMimeType(),
        ]);
        
        // Generate public URL
        $url = $this->generatePublicUrl($path);
        
        return [
            'path' => $path,
            'url' => $url,
            'filename' => $filename,
            'etag' => $result['ETag']
        ];
    }
    
    /**
     * Upload multiple images
     */
    public function uploadMultiple(array $files, string $folder = 'properties'): array
    {
        $uploaded = [];
        
        foreach ($files as $file) {
            $uploaded[] = $this->upload($file, $folder);
        }
        
        return $uploaded;
    }
    
    /**
     * Upload PDF document
     */
    public function uploadPDF(UploadedFile $file, string $folder = 'documents'): array
    {
        // Generate unique filename
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $path = "{$folder}/{$filename}";
        
        // Upload PDF directly (no optimization needed)
        $result = $this->s3Client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $path,
            'Body' => file_get_contents($file),
            'ContentType' => $file->getMimeType(),
        ]);
        
        // Generate public URL
        $url = $this->generatePublicUrl($path);
        
        return [
            'path' => $path,
            'url' => $url,
            'filename' => $filename,
            'etag' => $result['ETag']
        ];
    }
    
    /**
     * Delete file
     */
    public function delete(string $path): bool
    {
        try {
            // Check if file exists first
            if (!$this->exists($path)) {
                return false;
            }
            
            $this->s3Client->deleteObject([
                'Bucket' => $this->bucket,
                'Key' => $path
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Delete multiple files
     */
    public function deleteMultiple(array $paths): array
    {
        $results = [];
        foreach ($paths as $path) {
            $results[$path] = $this->delete($path);
        }
        return $results;
    }
    
    /**
     * Extract path from URL
     */
    public function extractPathFromUrl(string $url): string
    {
        // Extract path from Backblaze B2 URL
        // URL format: https://f003.backblazeb2.com/file/neuf-images/path/to/file.jpg
        $parsedUrl = parse_url($url);
        $path = $parsedUrl['path'] ?? '';
        
        // Remove bucket name from path
        $path = str_replace('/file/' . $this->bucket . '/', '', $path);
        
        return ltrim($path, '/');
    }
    
    /**
     * Check if file exists
     */
    public function exists(string $path): bool
    {
        try {
            $this->s3Client->headObject([
                'Bucket' => $this->bucket,
                'Key' => $path
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Get file size
     */
    public function size(string $path): int
    {
        try {
            $result = $this->s3Client->headObject([
                'Bucket' => $this->bucket,
                'Key' => $path
            ]);
            return $result['ContentLength'];
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Get file URL
     */
    public function url(string $path): string
    {
        return $this->generatePublicUrl($path);
    }
    
    /**
     * Generate public URL for file
     */
    private function generatePublicUrl(string $path): string
    {
        // Use the configured B2_URL as base
        $baseUrl = env('B2_URL', 'https://f003.backblazeb2.com/file/' . $this->bucket);
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }
    
    /**
     * Optimize image (resize if too large, compress)
     */
    private function optimizeImage(UploadedFile $file): string
    {
        // If GD is not available, return original file content
        if (!$this->imageManager) {
            return file_get_contents($file);
        }
        
        try {
            $image = $this->imageManager->read($file);
            
            // Resize if width > 1920px
            if ($image->width() > 1920) {
                $image->scaleDown(width: 1920);
            }
            
            // Compress (quality 85)
            return $image->toJpeg(85)->toString();
        } catch (\Exception $e) {
            // If optimization fails, return original file content
            return file_get_contents($file);
        }
    }
    
    /**
     * Get public URL for a given path
     */
    public function getUrl(string $path): string
    {
        // Use the configured B2_URL as base
        $baseUrl = env('B2_URL', 'https://f003.backblazeb2.com/file/' . $this->bucket);
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }
    
    /**
     * Get folder structure for different file types
     */
    public function getFolderStructure(): array
    {
        return [
            'promoters' => [
                'logos' => 'promoters/logos',
                'documents' => 'promoters/documents',
            ],
            'projects' => [
                'main_images' => 'projects/main-images',
                'galleries' => 'projects/galleries',
                'floor_plans' => 'projects/floor-plans',
                'brochures' => 'projects/brochures',
            ],
            'properties' => [
                'images' => 'properties/images',
            ]
        ];
    }
    
    /**
     * Validate file type
     */
    public function validateFileType(UploadedFile $file, string $type = 'image'): bool
    {
        $allowedTypes = [
            'image' => ['jpg', 'jpeg', 'png', 'webp'],
            'pdf' => ['pdf'],
            'document' => ['pdf', 'doc', 'docx']
        ];
        
        $extension = strtolower($file->getClientOriginalExtension());
        
        return in_array($extension, $allowedTypes[$type] ?? []);
    }
    
    /**
     * Get file info
     */
    public function getFileInfo(UploadedFile $file): array
    {
        return [
            'original_name' => $file->getClientOriginalName(),
            'extension' => $file->getClientOriginalExtension(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'size_human' => $this->formatBytes($file->getSize()),
        ];
    }
    
    /**
     * Format bytes to human readable
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}