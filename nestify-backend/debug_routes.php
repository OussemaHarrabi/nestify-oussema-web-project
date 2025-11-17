<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 ROUTE DEBUGGING\n";
echo "═══════════════════════════════════════\n\n";

// Test direct route matching
$router = app('router');
$routes = $router->getRoutes();

echo "Looking for /api/projects/5 route...\n\n";

// Get all routes
foreach ($routes as $route) {
    $uri = $route->uri();
    if (strpos($uri, 'api/projects') !== false && strpos($uri, '{') !== false) {
        $methods = implode('|', $route->methods());
        $action = $route->getActionName();
        echo "Route: {$methods} /{$uri}\n";
        echo "  Action: {$action}\n";
        echo "  Where: " . json_encode($route->wheres) . "\n\n";
    }
}

// Try to match the specific URL
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Testing URL matching...\n\n";

$request = \Illuminate\Http\Request::create('/api/projects/5', 'GET');
$request->headers->set('Accept', 'application/json');

try {
    $route = $router->getRoutes()->match($request);
    echo "✅ Route matched!\n";
    echo "  Route: " . $route->uri() . "\n";
    echo "  Action: " . $route->getActionName() . "\n";
    echo "  Parameters: " . json_encode($route->parameters()) . "\n";
} catch (\Exception $e) {
    echo "❌ Route NOT matched!\n";
    echo "  Error: " . $e->getMessage() . "\n";
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Testing /api/projects/residence-marina-bay...\n\n";

$request2 = \Illuminate\Http\Request::create('/api/projects/residence-marina-bay', 'GET');
$request2->headers->set('Accept', 'application/json');

try {
    $route2 = $router->getRoutes()->match($request2);
    echo "✅ Route matched!\n";
    echo "  Route: " . $route2->uri() . "\n";
    echo "  Action: " . $route2->getActionName() . "\n";
} catch (\Exception $e) {
    echo "❌ Route NOT matched!\n";
    echo "  Error: " . $e->getMessage() . "\n";
}
