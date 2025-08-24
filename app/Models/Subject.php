<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'description',
        'sub_description',
        'stage_id',
        'image',
        'visible',
    ];

    public function getImageAttribute($value)
    {
        return $value ? url('storage/' . $value) : asset('assets/images/images.png');
    }
    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function stages()
    {
        return $this->belongsToMany(Stage::class, StageSubjectTeacher::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, StageSubjectTeacher::class);
    }
}
