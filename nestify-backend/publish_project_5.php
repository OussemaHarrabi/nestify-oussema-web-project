<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "📢 Publishing All Projects\n";
echo "═══════════════════════════════════════\n\n";

$projects = \App\Models\Project::all();

echo "Found {$projects->count()} projects:\n\n";

foreach ($projects as $project) {
    echo "Project #{$project->id} - {$project->name}\n";
    echo "  Before: Published = " . ($project->is_published ? 'Yes' : 'No') . "\n";
    
    if (!$project->is_published) {
        $project->update([
            'is_published' => true,
            'published_at' => now()
        ]);
        echo "  ✅ Published!\n";
    } else {
        echo "  ℹ️  Already published\n";
    }
    echo "\n";
}

echo "═══════════════════════════════════════\n";
echo "✅ All projects are now published!\n";
