<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'name',
        'description',
        'visible',
        'file',
        'week_id',
    ];

    public function getFileAttribute($value)
    {
        return $value ? url('storage/' . $value) : asset('assets/images/images.png');
    }

    public function week()
    {
        return $this->belongsTo(Week::class);
    }
}
