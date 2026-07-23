<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meeting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'teacher_id',
        'class_id',
        'subject_id',
        'title',
        'description',
        'video_link',
        'date',
        'number',
        'video_link_status',
        'is_visible',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function attendance()
    {
        return $this->hasOne(Attendance::class);
    }

    /**
     * Get the schedule block corresponding to this meeting date and class/subject/teacher.
     */
    public function getScheduleBlockAttribute()
    {
        if (!$this->date || !$this->class_id || !$this->subject_id || !$this->teacher_id) {
            return null;
        }

        $daysIndo = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        $dayName = $daysIndo[\Carbon\Carbon::parse($this->date)->dayOfWeek] ?? null;

        if (!$dayName || $dayName === 'Minggu') {
            return null;
        }

        $schedules = Schedule::where('class_id', $this->class_id)
            ->where('subject_id', $this->subject_id)
            ->where('teacher_id', $this->teacher_id)
            ->where('day', $dayName)
            ->with(['timeSlot', 'schoolClass', 'subject', 'teacher'])
            ->get();

        if ($schedules->isEmpty()) {
            return null;
        }

        $blocks = Schedule::groupConsecutiveSchedules($schedules);
        return $blocks->first();
    }
}
