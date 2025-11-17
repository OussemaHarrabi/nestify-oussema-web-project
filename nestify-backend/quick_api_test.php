<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "🧪 API TESTING - QUICK DIAGNOSTIC\n";
echo "═══════════════════════════════════════\n\n";

$base = 'http://127.0.0.1:8000/api';
$stats = ['pass' => 0, 'fail' => 0];
$errors = [];

// Get tokens
$admin = User::where('email', 'admin@nestify.tn')->first();
$adminToken = $admin ? $admin->createToken('t')->plainTextToken : null;
$promoter = User::where('email', 'promoteur1@nestify.tn')->first();
$promoterToken = $promoter ? $promoter->createToken('t')->plainTextToken : null;

function test($url, $method = 'GET', $token = null, $body = null, $name = '') {
    global $stats, $errors, $base;
    $ch = curl_init("{$base}{$url}");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    $h = ['Accept: application/json'];
    if ($token) $h[] = "Authorization: Bearer {$token}";
    if ($body) { $h[] = 'Content-Type: application/json'; curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body)); }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
    $r = curl_exec($ch);
    $c = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $d = json_decode($r, true);
    $ok = $c >= 200 && $c < 300;
    if ($ok) { $stats['pass']++; echo "✅"; } else { $stats['fail']++; echo "❌"; $errors[] = ['name' => $name, 'code' => $c, 'msg' => $d['message'] ?? $d['error'] ?? 'Unknown', 'data' => $d]; }
    echo " [{$c}] {$name}\n";
    return ['code' => $c, 'data' => $d];
}

echo "AUTH:\n";
test('/login', 'POST', null, ['email' => 'admin@nestify.tn', 'password' => 'admin123'], 'Login');
test('/user', 'GET', $adminToken, null, 'Current User');

echo "\nADMIN:\n";
test('/admin/dashboard', 'GET', $adminToken, null, 'Dashboard');
$p = test('/admin/promoters', 'GET', $adminToken, null, 'Promoters');
$pid = $p['data']['data'][0]['id'] ?? null;
if ($pid) test("/admin/promoters/{$pid}", 'GET', $adminToken, null, "Promoter#{$pid}");
test('/admin/promoters?verified=false', 'GET', $adminToken, null, 'Pending');
$pr = test('/admin/projects', 'GET', $adminToken, null, 'Projects');
$prid = $pr['data']['data'][0]['id'] ?? null;
if ($prid) {
    test("/admin/projects/{$prid}", 'GET', $adminToken, null, "Project#{$prid}");
    test("/admin/projects/{$prid}/publish", 'PATCH', $adminToken, null, "Publish");
}

if ($promoterToken) {
    echo "\nPROMOTER:\n";
    test('/promoter/dashboard', 'GET', $promoterToken, null, 'Dashboard');
    test('/promoter/profile', 'GET', $promoterToken, null, 'Profile');
    test('/promoter/stats', 'GET', $promoterToken, null, 'Stats');
    test('/promoter/projects', 'GET', $promoterToken, null, 'Projects');
    test('/promoter/properties', 'GET', $promoterToken, null, 'Properties');
    test('/promoter/leads', 'GET', $promoterToken, null, 'Leads');
    test('/promoter/leads/stats', 'GET', $promoterToken, null, 'Lead Stats');
}

echo "\nPUBLIC:\n";
test('/projects', 'GET', null, null, 'Projects');
if ($prid) test("/projects/{$prid}", 'GET', null, null, "Project#{$prid}");
test('/projects?city=Tunis', 'GET', null, null, 'Search City');
test('/properties', 'GET', null, null, 'Properties');
test('/properties?type=appartement', 'GET', null, null, 'Filter Type');
test('/search?query=residence', 'GET', null, null, 'Search');

echo "\n═══════════════════════════════════════\n";
echo "RESULTS: {$stats['pass']} ✅  {$stats['fail']} ❌\n";

if ($stats['fail'] > 0) {
    echo "\n❌ FAILURES:\n";
    foreach ($errors as $i => $e) {
        echo "\n" . ($i+1) . ". {$e['name']}\n";
        echo "   HTTP {$e['code']}: {$e['msg']}\n";
        if (isset($e['data']['errors'])) {
            foreach ($e['data']['errors'] as $f => $v) {
                echo "   - {$f}: " . implode(', ', (array)$v) . "\n";
            }
        }
    }
}
echo "\n═══════════════════════════════════════\n";
