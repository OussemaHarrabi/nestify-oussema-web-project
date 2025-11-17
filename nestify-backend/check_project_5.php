<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Project;

echo "🔍 Checking Project 5 Status\n";
echo "═══════════════════════════════════════\n\n";

$project = Project::find(5);

if (!$project) {
    echo "❌ Project 5 does not exist!\n";
    exit(1);
}

echo "Project Details:\n";
echo "  ID: {$project->id}\n";
echo "  Name: {$project->name}\n";
echo "  Slug: {$project->slug}\n";
echo "  Is Published: " . ($project->is_published ? 'YES ✅' : 'NO ❌') . "\n";
echo "  Published At: " . ($project->published_at ?? 'NULL') . "\n";

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

if (!$project->is_published) {
    echo "\n⚠️  PROJECT IS NOT PUBLISHED!\n";
    echo "This is why the public route returns 404.\n";
    echo "The controller filters by is_published = true.\n\n";
    
    echo "Publishing now...\n";
    $project->is_published = true;
    $project->published_at = now();
    $project->save();
    
    echo "✅ Project published!\n";
    
    // Verify
    $project->refresh();
    echo "\nVerification:\n";
    echo "  Is Published: " . ($project->is_published ? 'YES ✅' : 'NO ❌') . "\n";
} else {
    echo "\n✅ Project IS published - should work!\n";
}
