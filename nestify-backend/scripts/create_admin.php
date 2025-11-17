<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Create admin user
$admin = new \App\Models\User();
$admin->name = 'Admin Nestify';
$admin->email = 'admin@nestify.tn';
$admin->password = bcrypt('admin123');
$admin->phone = '+216 00 000 000';
$admin->user_type = 'admin';
$admin->is_active = true;
$admin->save();

echo "\n✅ Admin user created successfully!\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📧 Email: admin@nestify.tn\n";
echo "🔑 Password: admin123\n";
echo "👤 Type: admin\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
