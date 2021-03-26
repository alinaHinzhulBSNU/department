<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//use User and Group?

class Student extends Model
{
    use HasFactory;

    public $timestamps = false;

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

    public function grades(){
        return $this->hasMany(
            Grade::class,
            'student_id',
            'id'
        );
    }

    // Average grade for rating 
    public function rating(){
        $sum = 0;
        $creditsSum = 0;
        $result = 0;

        foreach($this->grades as $grade){
            $sum += $grade->grade * $grade->subject->credit;
            $creditsSum += $grade->subject->credit;
        }

        if($creditsSum > 0){
            $result = round($sum / $creditsSum, 2);
        }

        return $result;
    }
}
