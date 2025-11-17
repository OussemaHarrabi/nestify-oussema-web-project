<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Project;

echo "📋 Projects in Database:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$projects = Project::select('id', 'name', 'is_published')->get();

if ($projects->count() === 0) {
    echo "❌ No projects found!\n";
} else {
    foreach ($projects as $project) {
        $status = $project->is_published ? '✅ Published' : '⏳ Not Published';
        echo "ID: {$project->id} - {$project->name} ({$status})\n";
    }
}

echo "\nTotal: {$projects->count()} projects\n";
