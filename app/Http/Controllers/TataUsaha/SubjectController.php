<?php

namespace App\Http\Controllers\TataUsaha;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(): View
    {
        $subjects = Subject::with('teacher')->orderBy('name')->paginate(20);

        return view('tatausaha.subjects.index', compact('subjects'));
    }

    public function create(): View
    {
        $teachers = Teacher::with('user')->orderBy('id')->get();

        return view('tatausaha.subjects.create', compact('teachers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:subjects,name'],
            'code' => ['nullable', 'string', 'max:50'],
            'major' => ['required', 'string', 'max:20'],
            'teacher_id' => ['nullable', 'exists:teachers,id'],
        ]);

        Subject::create($data);

        return redirect()->route('tatausaha.subjects.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(Subject $subject): View
    {
        $teachers = Teacher::with('user')->orderBy('id')->get();

        return view('tatausaha.subjects.edit', compact('subject', 'teachers'));
    }

    public function update(Request $request, Subject $subject): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:subjects,name,' . $subject->id],
            'code' => ['nullable', 'string', 'max:50'],
            'major' => ['required', 'string', 'max:20'],
            'teacher_id' => ['nullable', 'exists:teachers,id'],
        ]);

        $subject->update($data);

        return redirect()->route('tatausaha.subjects.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        $subject->delete();

        return redirect()->route('tatausaha.subjects.index')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
