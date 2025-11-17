<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "Checking current user types in database...\n";

try {
    $userTypes = User::select('user_type')->distinct()->get()->pluck('user_type');
    echo "Current user types: " . $userTypes->implode(', ') . "\n";
    
    // Check if there are any users
    $userCount = User::count();
    echo "Total users: {$userCount}\n";
    
    if ($userCount > 0) {
        $users = User::select('id', 'name', 'user_type')->get();
        foreach ($users as $user) {
            echo "User {$user->id}: {$user->name} - {$user->user_type}\n";
        }
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

