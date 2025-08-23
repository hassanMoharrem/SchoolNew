<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'visible',
    ];

    public function getImageAttribute($value)
    {
        return $value ? url('storage/' . $value) : asset('assets/images/images.png');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, StageSubjectTeacher::class);
    }
    
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, StageSubjectTeacher::class);
    }
}
