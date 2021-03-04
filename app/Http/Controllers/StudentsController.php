<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\User; 
use App\Models\Group; 
use App\Models\Student; 

class StudentsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    //Route::get('/students', [\App\Http\Controllers\StudentsController::class, 'index']);
    public function index()
    {
        //are visible to all? (if not - Add Gate!)
        $students = Student::all()->sortBy('group_id'); //sort by what? 
        $users = User::all(); 
        return view('students/index', ['students' => $students, 'users' => $users]); 
    }

    //Route::get('/students/create', [\App\Http\Controllers\StudentsController::class, 'create']);
    public function create()
    {
        if(Gate::allows('admin')){
            $roles = config('enums.roles'); 
            $users = User::where('role', '!=', $roles['STUDENT'])->get(); //not already a student  
            $groups = Group::all(); 
            return view('students/create', ['users' => $users, 'groups' => $groups]); 
        }else{
            return redirect('/'); 
        }
    }

    //Route::post('/students', [\App\Http\Controllers\StudentsController::class, 'store']);
    public function store(Request $request)
    {
        if(Gate::allows('admin')){
            //add data to students table
            $student = new Student();
            $data = $this->validateData($request);
           
          
            $student->user_id = $data['user_id'];
            $student->group_id = $data['group_id']; 
            
            $student->is_class_leader = $data['is_class_leader']; 
            $student->has_grant = $data['has_grant']; 
            $student->has_social_grant = $data['has_social_grant']; 

            $student->save();

            //change role of the user: 
            $roles = config('enums.roles');
            $student->user->role = $roles['STUDENT'];
            $student->user->save();

            return redirect('/students');
        }else{
            return redirect('/');
        }
    }

    //Route::get('/students/{id}/edit', [\App\Http\Controllers\StudentsController::class, 'edit']);
    public function edit($id)
    {
        if(Gate::allows('admin')){
            $student = Student::find($id);
            $users = User::all()->sortBy('name');
            $groups = Group::all(); 
            return view('students/edit', ['student' => $student, 'users' => $users, 'groups' => $groups]);
        }else{
            return redirect('/');
        }
    }

    //Route::patch('/students/{id}', [\App\Http\Controllers\StudentsController::class, 'update']);
    public function update($id)
    {
        if(Gate::allows('admin')){
            $student = Student::find($id);
            $data = $this->validateData(\request());

            $student->group_id = $data['group_id']; 
            $student->user_id = $data['user_id']; 
            $student->is_class_leader = $data['is_class_leader']; 
            $student->has_grant = $data['has_grant']; 
            $student->has_social_grant = $data['has_social_grant']; 

            $student->save();
    
            return redirect('/students');
        }else{
            return redirect('/');
        }
    }

    //Route::delete('/students/{id}', [\App\Http\Controllers\StudentsController::class, 'destroy']);
    public function destroy($id)
    {
        if(Gate::allows('admin')){
            //delete data from students table 
            $student = Student::find($id);
            $student->delete();

            //change role of the user
            $roles = config('enums.roles');
            //added NONE role to enum so the account can be used again to create a student or a teacher: 
            //to delete the account altogether we can use "USERS" -> delete 
            $student->user->role = $roles['NONE']; //set role to none if one stops being a student 
            $student->user->save();
    
            return redirect('/students');
        }else{
            return redirect('/');
        }
    }

    //VALIDATE
    private function validateData($data){
        return $this->validate($data, [
            'group_id' => ['required'],
            'user_id' => ['required'],
            'is_class_leader' => ['required'], 
            'has_grant' => ['required'], 
            'has_social_grant' => ['required'], 
        ], [
            'group_id.required' => 'Номер групи має бути вказаний!',
            'user_id.required' => 'Відповідний обліковий запис має бути обраний!',
            'is_class_leader.required' => 'Потрібно вказати, чи є студент старостою!',
            'has_grant.required' => 'Потрібно вказати, чи має студент стипендію!',
            'has_social_grant' => ['Потрібно вказати, чи має студент соціальну стипендію!'], 
        ]);
    }

    //SEARCH
    public function search(Request $request){
        if(Gate::allows('admin')){
            $students = Student::all();
            $name =  $request->input('name');

            if($name){
                $found_students = array();

                foreach ($students as $student){
                    if(mb_stristr($student->user->name, $name, false, 'UTF-8') !== false){
                        array_push($found_students, $student);
                    }
                }

                return view('students/index', ['students' => $found_students]);
            }

            return view('students/index', ['students' => $students]);
        }else{
            return redirect('/');
        }
    }
}
