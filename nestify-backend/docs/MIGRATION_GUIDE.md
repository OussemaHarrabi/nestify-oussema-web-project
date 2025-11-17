# Nestify Backend Migration Guide

## Overview
This guide will help you migrate from the agency-based system to the promoter-centric, project-based system.

## Migration Steps

### Step 1: Backup Your Database
```bash
# For SQLite
cp database/database.sqlite database/database.backup.sqlite

# For MySQL
mysqldump -u username -p nestify_db > backup.sql
```

### Step 2: Run New Migrations
```bash
# Create the new tables
php artisan migrate

# This will create:
# - promoters table
# - projects table
# - leads table
# And modify:
# - properties table (add project_id and new fields)
```

### Step 3: Migrate Existing Data

#### A. Convert Agencies to Promoters
Run this migration script:

```bash
php artisan migrate:agencies-to-promoters
```

Or manually in Tinker:
```php
php artisan tinker

// Convert each agency to a promoter
use App\Models\Agency;
use App\Models\Promoter;

Agency::all()->each(function($agency) {
    Promoter::create([
        'user_id' => $agency->user_id,
        'company_name' => $agency->company_name,
        'description' => $agency->description,
        'website' => $agency->website,
        'primary_phone' => $agency->user->phone ?? '',
        'primary_email' => $agency->user->email,
        'additional_phones' => $agency->additional_phones,
        'headquarters_address' => $agency->addresses[0] ?? null,
        'verified' => $agency->verified,
        'license_number' => $agency->license_number,
        'established_date' => $agency->established_date,
    ]);
});
```

#### B. Update User Types
```php
// Change all 'agency' users to 'promoter'
\App\Models\User::where('user_type', 'agency')->update(['user_type' => 'promoter']);
```

#### C. Create Default Projects for Existing Properties
```php
use App\Models\Property;
use App\Models\Project;
use App\Models\Promoter;

// Group properties by user_id (promoter)
$propertiesByPromoter = Property::with('user')->get()->groupBy('user_id');

foreach ($propertiesByPromoter as $userId => $properties) {
    $promoter = Promoter::where('user_id', $userId)->first();
    
    if (!$promoter) continue;
    
    // Group properties by city to create projects
    $propertiesByCity = $properties->groupBy('city');
    
    foreach ($propertiesByCity as $city => $cityProperties) {
        // Create a project for this city
        $project = Project::create([
            'promoter_id' => $promoter->id,
            'name' => "Projet {$city} - " . $promoter->company_name,
            'description' => "Collection de propriétés à {$city}",
            'reference' => 'PRJ-' . date('Y') . '-' . str_pad($promoter->id, 4, '0', STR_PAD_LEFT),
            'city' => $city,
            'address' => $cityProperties->first()->address ?? '',
            'district' => $cityProperties->first()->district,
            'latitude' => $cityProperties->first()->latitude,
            'longitude' => $cityProperties->first()->longitude,
            'status' => 'under_construction',
            'total_units' => $cityProperties->count(),
            'available_units' => $cityProperties->where('validated', true)->count(),
            'is_published' => true,
            'published_at' => now(),
        ]);
        
        // Assign properties to this project
        $cityProperties->each(function($property) use ($project) {
            $property->update([
                'project_id' => $project->id,
                'availability_status' => $property->validated ? 'available' : 'not_available',
            ]);
        });
        
        $project->updateUnitCounts();
    }
    
    $promoter->updateProjectCounts();
}
```

### Step 4: Update User Enum in Users Table
```bash
php artisan make:migration update_user_type_enum_in_users_table
```

```php
// In the migration file:
public function up()
{
    DB::statement("ALTER TABLE users MODIFY COLUMN user_type ENUM('admin', 'promoter', 'client') DEFAULT 'client'");
}

public function down()
{
    DB::statement("ALTER TABLE users MODIFY COLUMN user_type ENUM('admin', 'agency', 'client') DEFAULT 'client'");
}
```

### Step 5: Drop Old Agency Table (Optional - after verifying migration)
```bash
php artisan make:migration drop_agencies_table
```

```php
public function up()
{
    Schema::dropIfExists('agencies');
}
```

### Step 6: Update API Routes
Update your `routes/api.php`:

```php
// Replace all 'agency' routes with 'promoter' routes
// Old: Route::middleware('user.type:agency')->group(...)
// New: Route::middleware('user.type:promoter')->group(...)

// Replace agency endpoints
// Old: /api/agency/dashboard
// New: /api/promoter/dashboard
```

### Step 7: Update Middleware
Update or create `app/Http/Middleware/PromoterMiddleware.php`:

```php
public function handle($request, Closure $next)
{
    if (auth()->user()->user_type !== 'promoter') {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    return $next($request);
}
```

### Step 8: Seed Mock Projects (for development)
```bash
php artisan db:seed --class=ProjectSeeder
```

## Verification Checklist

- [ ] All agencies converted to promoters
- [ ] All properties assigned to projects
- [ ] User types updated (agency → promoter)
- [ ] Project counts calculated correctly
- [ ] Unit counts in projects are accurate
- [ ] All API routes updated
- [ ] Frontend updated to use new endpoints
- [ ] Authentication working for promoters
- [ ] Lead forms capturing data correctly

## Rollback Plan
If something goes wrong:

```bash
# Restore from backup
cp database/database.backup.sqlite database/database.sqlite

# Or for MySQL
mysql -u username -p nestify_db < backup.sql
```

## Testing After Migration

1. **Test Promoter Login**
```bash
php artisan tinker
$user = User::where('user_type', 'promoter')->first();
// Try logging in with this user
```

2. **Test Project-Property Relationships**
```php
$project = Project::first();
$project->properties; // Should return properties
$project->promoter; // Should return promoter
```

3. **Test Lead Creation**
```php
Lead::create([
    'promoter_id' => 1,
    'project_id' => 1,
    'property_id' => 1,
    'name' => 'Test Client',
    'email' => 'test@example.com',
    'phone' => '+216 12 345 678',
    'type' => 'contact_request',
]);
```

## Notes
- Keep the agencies table for 30 days before dropping it
- Monitor logs for any errors after migration
- Update frontend components gradually
- Test all CRUD operations thoroughly

