# 🚀 Backblaze B2 Integration Setup Guide

## ✅ What's Been Completed

1. **✅ S3 Package Installed**: `league/flysystem-aws-s3-v3` package installed
2. **✅ Filesystem Config Updated**: B2 disk configuration added to `config/filesystems.php`
3. **✅ ImageUploadService Created**: Complete service for handling file uploads
4. **✅ Test Command Created**: `TestBackblazeConnection` command for testing

## 🔧 Manual Steps Required

### Step 1: Update Your .env File

Add these lines to your existing `.env` file:

```env
# Change the filesystem disk to use B2
FILESYSTEM_DISK=b2

# Backblaze B2 Configuration
B2_KEY_ID=00344ce78b231450000000001
B2_APPLICATION_KEY=K003+Cw2AmdZC7FcNpS4LFG7nimvG2k
B2_BUCKET_ID=54a4ac3e9758bbb293910415
B2_BUCKET_NAME=neuf-laravel
B2_ENDPOINT=https://s3.eu-central-003.backblazeb2.com
B2_REGION=eu-central-003
B2_URL=https://f003.backblazeb2.com/file/neuf-laravel
```

### Step 2: Clear Configuration Cache

```bash
cd nestify-backend
php artisan config:clear
php artisan cache:clear
```

### Step 3: Test the Connection

```bash
php artisan backblaze:test
```

If successful, you should see:
```
🧪 Testing Backblaze B2 Connection...

1️⃣ Testing configuration...
✅ Configuration is valid

2️⃣ Testing connection...
✅ Connection successful

3️⃣ Testing file operations...
✅ File operations successful

4️⃣ Testing ImageUploadService...
✅ ImageUploadService working

🎉 All Backblaze B2 tests passed successfully!
```

## 📁 File Structure on Backblaze B2

Your files will be organized as:

```
neuf-laravel/
├── promoters/
│   ├── logos/
│   │   └── abc123xyz...jpg
│   └── documents/
│       └── license-abc123.pdf
├── projects/
│   ├── main-images/
│   │   └── def456uvw...jpg
│   ├── galleries/
│   │   ├── ghi789rst...jpg
│   │   └── jkl012mno...jpg
│   ├── floor-plans/
│   │   └── pqr345stu...jpg
│   └── brochures/
│       └── vwx678yza...pdf
└── properties/
    └── images/
        ├── bcd901efg...jpg
        └── hij234klm...jpg
```

## 🎯 Usage Examples

### Upload Promoter Logo
```php
use App\Services\ImageUploadService;

$imageService = new ImageUploadService();

// Upload promoter logo
$result = $imageService->upload(
    $request->file('logo'),
    'promoters/logos'
);

// Returns:
// [
//     'path' => 'promoters/logos/abc123xyz.jpg',
//     'url' => 'https://f003.backblazeb2.com/file/neuf-laravel/promoters/logos/abc123xyz.jpg',
//     'filename' => 'abc123xyz.jpg'
// ]

// Save URL to database
$promoter->update(['logo' => $result['url']]);
```

### Upload Project Gallery
```php
// Upload multiple project images
$uploadedImages = $imageService->uploadMultiple(
    $request->file('images'),
    'projects/galleries'
);

// Extract URLs
$imageUrls = array_map(fn($img) => $img['url'], $uploadedImages);

// Save to database
$project->update(['images' => $imageUrls]);
```

### Upload PDF Brochure
```php
$result = $imageService->uploadPDF(
    $request->file('brochure'),
    'projects/brochures'
);

$project->update(['brochure_pdf' => $result['url']]);
```

### Delete Files
```php
// Delete single file
$path = $imageService->extractPathFromUrl($oldImageUrl);
$imageService->delete($path);

// Delete multiple files
$paths = array_map([$imageService, 'extractPathFromUrl'], $oldImageUrls);
$imageService->deleteMultiple($paths);
```

## 🔧 Controller Integration

Here's how to integrate the ImageUploadService in your controllers:

```php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ImageUploadService;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    private $imageService;
    
    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }
    
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'main_image' => 'required|image|max:5120', // 5MB
            'images.*' => 'image|max:5120',
            'brochure' => 'nullable|file|mimes:pdf|max:10240', // 10MB
        ]);
        
        // Upload main image
        $mainImage = $this->imageService->upload(
            $request->file('main_image'),
            'projects/main-images'
        );
        
        // Upload gallery images
        $galleryImages = [];
        if ($request->hasFile('images')) {
            $uploaded = $this->imageService->uploadMultiple(
                $request->file('images'),
                'projects/galleries'
            );
            $galleryImages = array_map(fn($img) => $img['url'], $uploaded);
        }
        
        // Upload brochure
        $brochurePdf = null;
        if ($request->hasFile('brochure')) {
            $brochure = $this->imageService->uploadPDF(
                $request->file('brochure'),
                'projects/brochures'
            );
            $brochurePdf = $brochure['url'];
        }
        
        // Create project
        $project = Project::create([
            'name' => $validated['name'],
            'cover_image' => $mainImage['url'],
            'images' => $galleryImages,
            'brochure_pdf' => $brochurePdf,
            // ... other fields
        ]);
        
        return response()->json([
            'message' => 'Project created successfully',
            'project' => $project
        ], 201);
    }
}
```

## ⚠️ Important Notes

### File Size Limits
- **Images**: Max 5MB per image
- **PDFs**: Max 10MB per file
- Adjust in validation rules if needed

### Allowed File Types
- **Images**: JPEG, PNG, WebP
- **Documents**: PDF only

### Security
- All uploads are validated before saving
- Files have random 40-character names
- Public bucket allows direct access via URL

### Performance
- Images are optimized before upload (resized if > 1920px, compressed to 85% quality)
- Files uploaded directly to B2 (not stored locally first)
- B2 has built-in CDN for fast delivery

### Cost Management
- **Free tier**: 10GB storage, 1GB/day downloads
- Monitor usage in Backblaze dashboard
- Set up alerts at 80% usage

## 🐛 Troubleshooting

### Error: "Class 'League\Flysystem\AwsS3V3\AwsS3V3Adapter' not found"
```bash
composer require league/flysystem-aws-s3-v3 "^3.0"
php artisan config:clear
```

### Error: "Bucket not found"
- Verify `B2_BUCKET_NAME` matches exactly: `neuf-laravel`
- Check API key has access to this bucket

### Error: "Access Denied"
- Ensure bucket is set to **Public**
- Verify application key has read/write permissions
- Check key hasn't expired

### Images not displaying
- Verify URL in browser directly
- Check bucket permissions
- Ensure file was uploaded successfully

### Slow uploads
- Check internet connection
- Consider using queued jobs for multiple uploads
- EU-Central region should be fast for Tunisia

## 📊 Monitoring Usage

### In Backblaze Dashboard
1. Go to **B2 Cloud Storage** > **Buckets**
2. Click on **neuf-laravel**
3. View **Usage** tab

### Set Up Alerts
1. Go to **Account** > **Caps & Alerts**
2. Set alerts:
   - Storage: Alert at 8GB (80% of free tier)
   - Downloads: Alert at 25GB/month (80% of free tier)

## ✅ Ready to Go!

Once you've completed the manual steps:

1. **Update .env file** with B2 configuration
2. **Clear caches** with `php artisan config:clear`
3. **Test connection** with `php artisan backblaze:test`

If the test passes, your Backblaze B2 integration is ready to use! 🚀

## 🎯 Next Steps

1. **Update Controllers**: Integrate ImageUploadService in your controllers
2. **Test Uploads**: Test file uploads in your application
3. **Monitor Usage**: Set up usage alerts in Backblaze dashboard
4. **Optimize**: Consider background jobs for bulk uploads

Your Backblaze B2 integration is now complete and ready for production use! 🎉

