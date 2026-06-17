<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\QuestionAnswer;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentSubmissionController extends Controller
{
    public function index(): View
    {
        $student = Student::where('user_id', Auth::id())->first();

        $assignments = Assignment::query()
            ->when($student?->class_id, fn ($q, $classId) => $q->where('class_id', $classId))
            ->latest('due_at')
            ->get();

        // Load existing submissions for this student
        if ($student) {
            $submittedIds = AssignmentSubmission::where('student_id', $student->id)
                ->pluck('assignment_id')
                ->toArray();
        } else {
            $submittedIds = [];
        }

        return view('siswa.assignments.index', compact('assignments', 'student', 'submittedIds'));
    }

    /**
     * Show an online assignment for the student to answer
     */
    public function show(Assignment $assignment): View
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        
        // Verify student belongs to this class
        abort_unless($assignment->class_id == $student->class_id, 403);

        $assignment->load('questions.options');

        // Check if already submitted
        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->first();

        $answers = [];
        if ($submission) {
            $submission->load('questionAnswers.selectedOption');
            // Index answers by question_id for easy lookup
            $answers = $submission->questionAnswers->keyBy('question_id');
        }

        return view('siswa.assignments.show', compact('assignment', 'student', 'submission', 'answers'));
    }

    /**
     * Submit answers for a PDF or online assignment
     */
    public function store(Request $request, Assignment $assignment): RedirectResponse
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        // Enforce deadline — tolak submission jika deadline sudah lewat
        if ($assignment->due_at && Carbon::parse($assignment->due_at)->isPast()) {
            return back()->withErrors(['general' => 'Batas waktu pengumpulan tugas ini sudah lewat. Anda tidak dapat mengumpulkan tugas lagi.']);
        }

        if ($assignment->isOnline()) {
            return $this->storeOnline($request, $assignment, $student);
        }

        return $this->storePdf($request, $assignment, $student);
    }

    /**
     * Handle PDF assignment submission (existing behavior)
     */
    private function storePdf(Request $request, Assignment $assignment, Student $student): RedirectResponse
    {
        $data = $request->validate([
            'answer_text' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions', 'public');
        }

        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->first();

        if ($submission) {
            if ($filePath && $submission->file_path) {
                Storage::disk('public')->delete($submission->file_path);
            }
            $submission->update([
                'answer_text' => $data['answer_text'] ?? $submission->answer_text,
                'file_path' => $filePath ?? $submission->file_path,
                'submitted_at' => now(),
            ]);
        } else {
            AssignmentSubmission::create([
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
                'answer_text' => $data['answer_text'] ?? null,
                'file_path' => $filePath,
                'submitted_at' => now(),
            ]);
        }

        return back()->with('success', 'Tugas berhasil dikirim.');
    }

    /**
     * Handle online assignment submission with validation that ALL questions must be answered
     */
    private function storeOnline(Request $request, Assignment $assignment, Student $student): RedirectResponse
    {
        $assignment->load('questions.options');
        $questions = $assignment->questions;

        // Check if already submitted
        $existingSubmission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existingSubmission) {
            return back()->withErrors(['general' => 'Anda sudah mengumpulkan tugas ini.']);
        }

        // Validate that ALL questions are answered
        $answersData = $request->input('answers', []);
        $unanswered = [];

        foreach ($questions as $question) {
            $answer = $answersData[$question->id] ?? null;

            if ($question->type === 'pilihan_ganda') {
                if (empty($answer['selected_option_id'])) {
                    $unanswered[] = $question->order;
                }
            } elseif ($question->type === 'isian_singkat') {
                if (empty(trim($answer['answer_text'] ?? ''))) {
                    $unanswered[] = $question->order;
                }
            } elseif ($question->type === 'essay') {
                if (empty(trim($answer['answer_text'] ?? ''))) {
                    $unanswered[] = $question->order;
                }
            }
        }

        if (!empty($unanswered)) {
            $nums = implode(', ', $unanswered);
            return back()->withInput()->withErrors([
                'general' => "Semua soal wajib dijawab. Soal nomor {$nums} belum dijawab."
            ]);
        }

        DB::beginTransaction();
        try {
            // Create the submission
            $submission = AssignmentSubmission::create([
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
                'submitted_at' => now(),
            ]);

            $totalScore = 0;
            $totalPoints = 0;

            foreach ($questions as $question) {
                $answer = $answersData[$question->id] ?? [];
                $isCorrect = null;
                $score = null;
                $selectedOptionId = null;
                $answerText = null;

                $totalPoints += $question->points;

                if ($question->type === 'pilihan_ganda') {
                    $selectedOptionId = $answer['selected_option_id'] ?? null;
                    // Auto-grade: check if selected option is correct
                    if ($selectedOptionId) {
                        $correctOption = $question->options->where('is_correct', true)->first();
                        $isCorrect = $correctOption && $correctOption->id == $selectedOptionId;
                        $score = $isCorrect ? $question->points : 0;
                        $totalScore += $score;
                    }
                } elseif ($question->type === 'isian_singkat') {
                    $answerText = trim($answer['answer_text'] ?? '');
                    // Auto-grade: case-insensitive exact match
                    if ($question->correct_answer) {
                        $isCorrect = mb_strtolower(trim($answerText)) === mb_strtolower(trim($question->correct_answer));
                        $score = $isCorrect ? $question->points : 0;
                        $totalScore += $score;
                    }
                } elseif ($question->type === 'essay') {
                    $answerText = trim($answer['answer_text'] ?? '');
                    // Essay: not auto-graded, teacher will grade manually
                    $isCorrect = null;
                    $score = null;
                }

                QuestionAnswer::create([
                    'question_id' => $question->id,
                    'student_id' => $student->id,
                    'assignment_submission_id' => $submission->id,
                    'answer_text' => $answerText,
                    'selected_option_id' => $selectedOptionId,
                    'is_correct' => $isCorrect,
                    'score' => $score,
                ]);
            }

            // Calculate percentage score (only from auto-graded questions)
            $hasEssay = $questions->where('type', 'essay')->count() > 0;
            if (!$hasEssay && $totalPoints > 0) {
                $percentage = round(($totalScore / $totalPoints) * 100);
                $submission->update(['score' => $percentage]);
            }
            // If there are essay questions, score stays null until teacher grades them

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['general' => 'Terjadi kesalahan saat menyimpan jawaban: ' . $e->getMessage()]);
        }

        return redirect()->route('siswa.assignments.show', $assignment)
            ->with('success', 'Jawaban berhasil dikirim!');
    }
}
