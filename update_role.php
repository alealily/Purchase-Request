<?php

// Update user role to match RBAC expected values
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

// Update Head of Department/Division/President Director to 'superior'
$updated = DB::table('users')
    ->where('email', 'dikta@siix-global.com')
    ->update(['role' => 'superior']);

if ($updated) {
    echo "SUCCESS! Role updated to 'superior' for dikta@siix-global.com\n";
} else {
    echo "ERROR: User not found or no update needed.\n";
}
