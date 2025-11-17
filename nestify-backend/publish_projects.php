<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Project;

echo "📋 Publishing Projects\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$projects = Project::where('is_published', false)->get();

if ($projects->count() === 0) {
    echo "✅ All projects are already published!\n";
} else {
    foreach ($projects as $project) {
        $project->is_published = true;
        $project->published_at = now();
        $project->save();
        echo "✅ Published: {$project->name} (ID: {$project->id})\n";
    }
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Summary:\n";
$total = Project::count();
$published = Project::where('is_published', true)->count();
echo "Total Projects: {$total}\n";
echo "Published: {$published}\n";
echo "Unpublished: " . ($total - $published) . "\n";
