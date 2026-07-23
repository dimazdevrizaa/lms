<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $fillable = [
        'academic_year_id',
        'class_id',
        'day',
        'time_slot_id',
        'subject_id',
        'activity',
        'teacher_id',
    ];

    public const DAYS = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function timeSlot(): BelongsTo
    {
        return $this->belongsTo(TimeSlot::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Group consecutive schedule items of the same subject, class, and teacher into consolidated time blocks.
     */
    public static function groupConsecutiveSchedules($schedules)
    {
        if ($schedules->isEmpty()) {
            return collect();
        }

        $sorted = $schedules->sortBy(fn($s) => $s->timeSlot->slot_order ?? $s->timeSlot->start_time ?? 0)->values();
        $grouped = collect();

        $currentBlock = null;

        foreach ($sorted as $schedule) {
            $classId = $schedule->class_id;
            $subjectId = $schedule->subject_id;
            $teacherId = $schedule->teacher_id;
            $activity = $schedule->activity;

            if ($currentBlock === null) {
                $currentBlock = [
                    'class_id' => $classId,
                    'subject_id' => $subjectId,
                    'teacher_id' => $teacherId,
                    'activity' => $activity,
                    'subject' => $schedule->subject,
                    'schoolClass' => $schedule->schoolClass,
                    'teacher' => $schedule->teacher,
                    'day' => $schedule->day,
                    'start_time' => $schedule->timeSlot->start_time ?? null,
                    'end_time' => $schedule->timeSlot->end_time ?? null,
                    'jp_count' => 1,
                    'first_slot_label' => $schedule->timeSlot->label ?? '',
                    'last_slot_label' => $schedule->timeSlot->label ?? '',
                    'first_schedule' => $schedule,
                ];
            } else {
                $prevOrder = $currentBlock['last_schedule']->timeSlot->slot_order ?? 0;
                $currOrder = $schedule->timeSlot->slot_order ?? 0;

                $prevEndTime = $currentBlock['last_schedule']->timeSlot->end_time ?? null;
                $currStartTime = $schedule->timeSlot->start_time ?? null;

                // Same subject, class & activity (resilient to teacher_id being null in one of the slots)
                $sameSubject = ($currentBlock['class_id'] == $classId
                    && $currentBlock['activity'] === $activity
                    && (
                        ($subjectId !== null && $currentBlock['subject_id'] == $subjectId)
                        || ($subjectId === null && $currentBlock['subject_id'] === null)
                    )
                    && (
                        $currentBlock['teacher_id'] == $teacherId
                        || !$currentBlock['teacher_id']
                        || !$teacherId
                    )
                );

                // Consecutive if slot_order is sequential OR start_time equals previous end_time
                $isConsecutive = ($currOrder === $prevOrder + 1)
                    || ($prevEndTime && $currStartTime && $prevEndTime === $currStartTime);

                if ($sameSubject && $isConsecutive) {
                    $currentBlock['end_time'] = $schedule->timeSlot->end_time ?? $currentBlock['end_time'];
                    $currentBlock['jp_count'] += 1;
                    $currentBlock['last_slot_label'] = $schedule->timeSlot->label ?? '';
                    if (!$currentBlock['teacher_id'] && $teacherId) {
                        $currentBlock['teacher_id'] = $teacherId;
                        $currentBlock['teacher'] = $schedule->teacher;
                    }
                } else {
                    $grouped->push(static::formatBlock($currentBlock));
                    $currentBlock = [
                        'class_id' => $classId,
                        'subject_id' => $subjectId,
                        'teacher_id' => $teacherId,
                        'activity' => $activity,
                        'subject' => $schedule->subject,
                        'schoolClass' => $schedule->schoolClass,
                        'teacher' => $schedule->teacher,
                        'day' => $schedule->day,
                        'start_time' => $schedule->timeSlot->start_time ?? null,
                        'end_time' => $schedule->timeSlot->end_time ?? null,
                        'jp_count' => 1,
                        'first_slot_label' => $schedule->timeSlot->label ?? '',
                        'last_slot_label' => $schedule->timeSlot->label ?? '',
                        'first_schedule' => $schedule,
                    ];
                }
            }
            $currentBlock['last_schedule'] = $schedule;
        }

        if ($currentBlock !== null) {
            $grouped->push(static::formatBlock($currentBlock));
        }

        return $grouped;
    }

    private static function formatBlock(array $block): object
    {
        $slotLabel = $block['first_slot_label'];
        if ($block['jp_count'] > 1) {
            $slotLabel = $block['first_slot_label'] . '–' . $block['last_slot_label'] . ' (' . $block['jp_count'] . ' JP)';
        } else {
            $slotLabel .= ' (1 JP)';
        }

        return (object) [
            'class_id' => $block['class_id'],
            'subject_id' => $block['subject_id'],
            'teacher_id' => $block['teacher_id'],
            'activity' => $block['activity'],
            'subject' => $block['subject'],
            'schoolClass' => $block['schoolClass'],
            'teacher' => $block['teacher'],
            'day' => $block['day'],
            'start_time' => $block['start_time'],
            'end_time' => $block['end_time'],
            'jp_count' => $block['jp_count'],
            'slot_label' => $slotLabel,
            'first_schedule' => $block['first_schedule'],
        ];
    }
}
