<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    public function subject(){
        return $this->belongsTo(
            Subject::class,
            'subject_id',
            'id'
        );
    }

    public function student(){
        return $this->belongsTo(
            Student::class,
            'student_id',
            'id'
        );
    }

    // Переведення в оцінку за національною шкалою
    public function toNational(){
        $result = "";

        if(0 <= $this->grade and $this->grade <= 59){
            $result = "Незадовільно (борг)";
        }
        else if(60 <= $this->grade and $this->grade <= 74){
            $result = "Задовільно";
        }
        else if(75 <= $this->grade and $this->grade <= 89){
            $result = "Добре";
        }
        else if(90 <= $this->grade and $this->grade <= 100){
            $result = "Відмінно";
        }
        
        return $result;
    }

    // Переведення в оцінку за шкалою ECTS
    public function toECTS(){
        $result = "ECTS";

        if(0 <= $this->grade and $this->grade <= 34){
            $result = "F";
        }
        else if(35 <= $this->grade and $this->grade <= 59){
            $result = "FX";
        }
        else if(60 <= $this->grade and $this->grade <= 66){
            $result = "E";
        }
        else if(67 <= $this->grade and $this->grade <= 74){
            $result = "D";
        }
        else if(75 <= $this->grade and $this->grade <= 81){
            $result = "C";
        }
        else if(82 <= $this->grade and $this->grade <= 89){
            $result = "B";
        }
        else if(90 <= $this->grade and $this->grade <= 100){
            $result = "A";
        }

        return $result;
    }
}
