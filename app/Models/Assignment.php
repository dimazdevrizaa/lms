<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'teacher_id',
        'class_id',
        'subject_id',
        'meeting_id',
        'type',
        'title',
        'description',
        'due_at',
        'file_path',
        'quiz_url',
    ];

    public function isOnline(): bool
    {
        return $this->type === 'online';
    }

    public function isExternal(): bool
    {
        return $this->type === 'external';
    }

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

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

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class)->orderBy('created_at', 'desc');
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }
}

