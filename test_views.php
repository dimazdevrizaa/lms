<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

echo "\n═══════════════════════════════════════════════════════════════\n";
echo "              VIEW RENDERING TEST\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Login as teacher
Auth::loginUsingId(3); // guru@sma15padang.test
echo "Logged in as: " . Auth::user()->name . " (" . Auth::user()->role . ")\n\n";

// Test 1: Guru assignments index
try {
    $response = app()->handle(\Illuminate\Http\Request::create('/guru/assignments', 'GET'));
    echo "✅ guru/assignments/index - Status: " . $response->getStatusCode() . "\n";
} catch (\Exception $e) {
    echo "❌ guru/assignments/index - Error: " . $e->getMessage() . "\n";
}

// Test 2: Guru assignment create
try {
    $response = app()->handle(\Illuminate\Http\Request::create('/guru/assignments/create', 'GET'));
    echo "✅ guru/assignments/create - Status: " . $response->getStatusCode() . "\n";
} catch (\Exception $e) {
    echo "❌ guru/assignments/create - Error: " . $e->getMessage() . "\n";
}

// Test 3: Guru assignment show (online)
try {
    $response = app()->handle(\Illuminate\Http\Request::create('/guru/assignments/3', 'GET'));
    echo "✅ guru/assignments/3 (show online) - Status: " . $response->getStatusCode() . "\n";
} catch (\Exception $e) {
    echo "❌ guru/assignments/3 (show online) - Error: " . $e->getMessage() . "\n";
}

// Test 4: Guru assignment edit (online)
try {
    $response = app()->handle(\Illuminate\Http\Request::create('/guru/assignments/3/edit', 'GET'));
    echo "✅ guru/assignments/3/edit (edit online) - Status: " . $response->getStatusCode() . "\n";
} catch (\Exception $e) {
    echo "❌ guru/assignments/3/edit (edit online) - Error: " . $e->getMessage() . "\n";
}

// Test 5: Guru assignment show (PDF - existing)
try {
    $response = app()->handle(\Illuminate\Http\Request::create('/guru/assignments/1', 'GET'));
    echo "✅ guru/assignments/1 (show pdf) - Status: " . $response->getStatusCode() . "\n";
} catch (\Exception $e) {
    echo "❌ guru/assignments/1 (show pdf) - Error: " . $e->getMessage() . "\n";
}

// Now test student views
Auth::loginUsingId(4); // siswa@sma15padang.test
echo "\nLogged in as: " . Auth::user()->name . " (" . Auth::user()->role . ")\n\n";

// Test 6: Student assignments index
try {
    $response = app()->handle(\Illuminate\Http\Request::create('/siswa/assignments', 'GET'));
    echo "✅ siswa/assignments (index) - Status: " . $response->getStatusCode() . "\n";
} catch (\Exception $e) {
    echo "❌ siswa/assignments (index) - Error: " . $e->getMessage() . "\n";
}

// Test 7: Student assignment show (online quiz form)
try {
    $response = app()->handle(\Illuminate\Http\Request::create('/siswa/assignments/3', 'GET'));
    echo "✅ siswa/assignments/3 (show online) - Status: " . $response->getStatusCode() . "\n";
} catch (\Exception $e) {
    echo "❌ siswa/assignments/3 (show online) - Error: " . $e->getMessage() . "\n";
}

echo "\n═══════════════════════════════════════════════════════════════\n";
echo "                ALL TESTS COMPLETE\n";
echo "═══════════════════════════════════════════════════════════════\n\n";
