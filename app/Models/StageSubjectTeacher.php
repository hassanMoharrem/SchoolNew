<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StageSubjectTeacher extends Model
{
    protected $fillable = [
        'stage_id',
        'subject_id',
        'teacher_id',
    ];

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
