<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//use User and Group?

class Student extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }

    public function group(){
        return $this->belongsTo(
            Group::class,
            'group_id',
            'id'
        );
    }
}
