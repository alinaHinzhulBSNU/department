<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    public function teacher(){
        return $this->belongsTo(
            Teacher::class,
            'teacher_id',
            'id'
        );
    }
}
