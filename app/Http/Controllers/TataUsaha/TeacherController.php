<?php

namespace App\Http\Controllers\TataUsaha;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(): View
    {
        $teachers = Teacher::with('user')->paginate(20);

        return view('tatausaha.teachers.index', compact('teachers'));
    }

    public function create(): View
    {
        return view('tatausaha.teachers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'nip' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        $tempPassword = Str::random(8);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($tempPassword),
        ]);
        $user->role = 'guru';
        $user->save();

        Teacher::create([
            'user_id' => $user->id,
            'nip' => $data['nip'] ?? null,
            'phone' => $data['phone'] ?? null,
        ]);

        return redirect()->route('tatausaha.teachers.index')
            ->with('success', "Data guru berhasil dibuat. Password sementara: {$tempPassword} — minta guru segera mengganti password.");
    }
    public function edit(Teacher $teacher): View
    {
        return view('tatausaha.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $teacher->user_id],
            'nip' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', 'string', Password::min(8)->mixedCase()->numbers()],
        ]);

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (! empty($data['password'])) {
            $userData['password'] = bcrypt($data['password']);
        }

        $teacher->user->update($userData);

        $teacher->update([
            'nip' => $data['nip'] ?? null,
            'phone' => $data['phone'] ?? null,
        ]);

        return redirect()->route('tatausaha.teachers.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        $user = $teacher->user;
        $teacher->delete();
        $user->delete();

        return redirect()->route('tatausaha.teachers.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }
}

