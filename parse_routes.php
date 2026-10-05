<?php
$routesFile = 'd:/xampp/htdocs/Amarbangla/amarbangla_CI4/app/Config/Routes.php';
$content = file_get_contents($routesFile);

$lines = explode("\n", $content);
$parsedRoutes = [
    'super_admin' => [],
    'shop_admin' => [],
    'agent' => [],
    'other' => []
];

foreach ($lines as $line) {
    $line = trim($line);
    if (preg_match('/\$routes->(get|post|match|put|delete)\s*\(\s*[\'"]([^\'"]+)[\'"]\s*,\s*[\'"]([^\'"]+)[\'"]\s*\)/', $line, $m)) {
        $method = $m[1];
        $uri = $m[2];
        $handler = $m[3];
        
        if (strpos($uri, 'super_admin') === 0) {
            $parsedRoutes['super_admin'][] = ['method' => $method, 'uri' => $uri, 'handler' => $handler];
        } elseif (strpos($uri, 'shop_admin') === 0) {
            $parsedRoutes['shop_admin'][] = ['method' => $method, 'uri' => $uri, 'handler' => $handler];
        } elseif (strpos($uri, 'agent') === 0) {
            $parsedRoutes['agent'][] = ['method' => $method, 'uri' => $uri, 'handler' => $handler];
        } else {
            $parsedRoutes['other'][] = ['method' => $method, 'uri' => $uri, 'handler' => $handler];
        }
    }
}

echo "Total parsed routes in CI4 Routes.php:\n";
echo " - super_admin: " . count($parsedRoutes['super_admin']) . "\n";
echo " - shop_admin: " . count($parsedRoutes['shop_admin']) . "\n";
echo " - agent: " . count($parsedRoutes['agent']) . "\n";
echo " - other: " . count($parsedRoutes['other']) . "\n";
