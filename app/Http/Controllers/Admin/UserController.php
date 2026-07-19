<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::orderBy('name')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers(), 'confirmed'],
            'role' => ['required', 'in:admin,tatausaha,guru,siswa'],
            'nip' => ['nullable', 'string', 'max:50'],
            'nis' => ['nullable', 'string', 'max:50'],
        ]);

        $data['password'] = bcrypt($data['password']);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
        $user->role = $data['role'];
        $user->save();

        // Buat entry di tabel relasi berdasarkan role
        if ($user->role === 'guru') {
            Teacher::create([
                'user_id' => $user->id,
                'nip' => $data['nip'] ?? null,
            ]);
        } elseif ($user->role === 'siswa') {
            Student::create([
                'user_id' => $user->id,
                'nis' => $data['nis'] ?? null,
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,tatausaha,guru,siswa'],
            'password' => ['nullable', 'string', Password::min(8)->mixedCase()->numbers(), 'confirmed'],
            'nip' => ['nullable', 'string', 'max:50'],
            'nis' => ['nullable', 'string', 'max:50'],
        ]);

        if (! empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        // role/nip/nis bukan bagian dari $fillable User — simpan dulu sebelum di-unset
        $role = $data['role'];
        $nip = $data['nip'] ?? null;
        $nis = $data['nis'] ?? null;
        unset($data['role'], $data['nip'], $data['nis']);

        $user->update($data);
        $user->role = $role;
        $user->save();

        // Update atau buat entry di tabel relasi berdasarkan role
        if ($user->role === 'guru') {
            if ($user->teacher) {
                $user->teacher->update(['nip' => $nip]);
            } else {
                Teacher::create([
                    'user_id' => $user->id,
                    'nip' => $nip,
                ]);
            }
        } elseif ($user->role === 'siswa') {
            if ($user->student) {
                $user->student->update(['nis' => $nis]);
            } else {
                Student::create([
                    'user_id' => $user->id,
                    'nis' => $nis,
                ]);
            }
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}

