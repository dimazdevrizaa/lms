<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(ClassAttendanceDetail::class);
    }
}
