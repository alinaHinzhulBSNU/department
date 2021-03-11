<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class GradePolicy
{
    use HandlesAuthorization;

    public function __construct(){}

    private function checkTeacher(User $user, Grade $grade){
        if($user->role == 'teacher'){
            return Subject::where('teacher_id', $user->teacher->id)
            ->where('id', $grade->subject->id)
            ->exists();
        }else{
            return false;
        }
    }

    public function update(User $user, Grade $grade){
        return $this->checkTeacher($user, $grade);
    }
}
