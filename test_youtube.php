<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Material;

$m = new Material([
    'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'
]);
echo "Full URL: " . $m->youtube_embed_url . "\n";

$m2 = new Material([
    'youtube_url' => 'https://youtu.be/dQw4w9WgXcQ?si=abcdef'
]);
echo "Short URL: " . $m2->youtube_embed_url . "\n";
