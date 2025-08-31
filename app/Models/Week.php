<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    protected $fillable = [
        'name',
        'description',
        'visible',
        'stage_subject_teacher_id',
    ];

    public function stage_subject_teacher()
    {
        return $this->belongsTo(StageSubjectTeacher::class);
    }

}
