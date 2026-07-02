<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
echo json_encode([
    'config_tz' => config('app.timezone'),
    'now' => now()->toDateTimeString(),
    'latest_order' => App\Models\order::latest()->first()->created_at->toDateTimeString()
]);
