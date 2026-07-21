<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function start(User $user): RedirectResponse
    {
        // Only allow admin to start an impersonation session
        abort_unless(Auth::user()->role === 'admin' || session()->has('impersonate_original_id'), 403, 'Unauthorized.');

        // Prevent self-impersonation or impersonating another admin
        if ($user->id === Auth::id() || $user->role === 'admin') {
            return redirect()->back()->with('error', 'Tidak dapat melakukan impersonasi akun admin.');
        }

        // Store original admin ID in session if not already in impersonation
        if (!session()->has('impersonate_original_id')) {
            session(['impersonate_original_id' => Auth::id()]);
        }

        Auth::login($user);

        // Redirect to target user's dashboard based on role
        return match ($user->role) {
            'tatausaha' => redirect()->route('tatausaha.dashboard')->with('success', "Menyamar sebagai {$user->name}"),
            'guru' => redirect()->route('guru.dashboard')->with('success', "Menyamar sebagai {$user->name}"),
            'siswa' => redirect()->route('siswa.dashboard')->with('success', "Menyamar sebagai {$user->name}"),
            default => redirect()->to('/')->with('success', "Menyamar sebagai {$user->name}"),
        };
    }

    public function stop(): RedirectResponse
    {
        abort_unless(session()->has('impersonate_original_id'), 403, 'Unauthorized.');

        $originalId = session()->pull('impersonate_original_id');
        Auth::loginUsingId($originalId);

        return redirect()->route('admin.users.index')->with('success', 'Kembali ke sesi Admin.');
    }
}
