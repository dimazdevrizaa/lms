<?php

namespace App\Http\Controllers\TataUsaha;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(): View
    {
        $teachers = Teacher::with('user')->paginate(20);

        return view('tata-usaha.teachers.index', compact('teachers'));
    }

    public function create(): View
    {
        return view('tata-usaha.teachers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'nip' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt('password'),
            'role' => 'guru',
        ]);

        Teacher::create([
            'user_id' => $user->id,
            'nip' => $data['nip'] ?? null,
            'phone' => $data['phone'] ?? null,
        ]);

        return redirect()->route('tatausaha.teachers.index')
            ->with('success', 'Data guru berhasil dibuat (password default: password).');
    }
    public function edit(Teacher $teacher): View
    {
        return view('tata-usaha.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $teacher->user_id],
            'nip' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', 'string', 'min:6'],
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

