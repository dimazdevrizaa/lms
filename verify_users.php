<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$users = User::with('adminStaff', 'teacher', 'student')->get();

echo "\n═══════════════════════════════════════════════════════════════\n";
echo "                    4 USER AKUN BERHASIL DIBUAT\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

foreach ($users as $user) {
    echo "👤 {$user->name} ({$user->role})\n";
    echo "   Email    : {$user->email}\n";
    echo "   Password : password\n";
    
    if ($user->adminStaff) {
        echo "   NIP      : {$user->adminStaff->nip}\n";
        echo "   Phone    : {$user->adminStaff->phone}\n";
        echo "   Position : {$user->adminStaff->position}\n";
    } elseif ($user->teacher) {
        echo "   NIP      : {$user->teacher->nip}\n";
        echo "   Phone    : {$user->teacher->phone}\n";
    } elseif ($user->student) {
        echo "   NIS      : {$user->student->nis}\n";
        echo "   Phone    : {$user->student->phone}\n";
        $className = $user->student->schoolClass ? $user->student->schoolClass->name : 'N/A';
        echo "   Class    : {$className}\n";
    }
    echo "\n";
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "Struktur Database:\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "✓ users table (auth & user data)\n";
echo "✓ admin_staff table (admin & tata usaha)\n";
echo "✓ teachers table (guru)\n";
echo "✓ students table (siswa)\n";
echo "═══════════════════════════════════════════════════════════════\n\n";
