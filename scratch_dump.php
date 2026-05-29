<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$data = App\Models\Peminjaman::with('user')->get()->toArray();
file_put_contents('dump.json', json_encode($data, JSON_PRETTY_PRINT));
echo "Done.";
