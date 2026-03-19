<?php

namespace App\Http\Controllers\TataUsaha;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Student::with(['user', 'schoolClass']);

        if ($request->has('major') && $request->major != '') {
            $query->whereHas('schoolClass', function($q) use ($request) {
                $q->where('major', $request->major);
            });
        }

        if ($request->has('class_id') && $request->class_id != '') {
            $query->where('class_id', $request->class_id);
        }

        $students = $query->paginate(20)->withQueryString();
        $classes = SchoolClass::orderBy('name')->get();

        return view('tata-usaha.students.index', compact('students', 'classes'));
    }

    public function create(): View
    {
        $classes = SchoolClass::orderBy('name')->get();

        return view('tata-usaha.students.create', compact('classes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'nis' => ['required', 'string', 'max:50', 'unique:students,nis'],
            'class_id' => ['nullable', 'exists:classes,id'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt('password'),
            'role' => 'siswa',
        ]);

        Student::create([
            'user_id' => $user->id,
            'nis' => $data['nis'],
            'class_id' => $data['class_id'] ?? null,
        ]);

        return redirect()->route('tatausaha.students.index')
            ->with('success', 'Data siswa berhasil dibuat (password default: password).');
    }

    public function edit(Student $student): View
    {
        $classes = SchoolClass::orderBy('name')->get();

        return view('tata-usaha.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $student->user_id],
            'nis' => ['required', 'string', 'max:50', 'unique:students,nis,' . $student->id],
            'class_id' => ['nullable', 'exists:classes,id'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (! empty($data['password'])) {
            $userData['password'] = bcrypt($data['password']);
        }

        $student->user->update($userData);

        $student->update([
            'nis' => $data['nis'],
            'class_id' => $data['class_id'] ?? null,
        ]);

        return redirect()->route('tatausaha.students.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        // User will be deleted automatically if cascade delete is set, but better be safe
        $user = $student->user;
        $student->delete();
        $user->delete();

        return redirect()->route('tatausaha.students.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }
}

