<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nisn',
        'phone',
        'class_id',
        'parent_code',
    ];

    protected static function booted()
    {
        static::creating(function ($student) {
            if (empty($student->parent_code)) {
                do {
                    // ponytail: 6 random alphanumeric characters (no prefix)
                    $code = strtoupper(\Illuminate\Support\Str::random(6));
                } while (static::where('parent_code', $code)->exists());
                $student->parent_code = $code;
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function behaviorRecords(): HasMany
    {
        return $this->hasMany(BehaviorRecord::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(StudentGrade::class);
    }
}

