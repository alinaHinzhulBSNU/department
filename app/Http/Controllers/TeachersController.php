<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Teacher;
use App\Models\User;

class TeachersController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    //Route::get('/teachers', [\App\Http\Controllers\TeachersController::class, 'index']);
    public function index(){
        if(Gate::allows('admin')){
            $teachers = Teacher::all()->sortBy('department');
            return view('teachers/index', ['teachers' => $teachers]);
        }else{
            return redirect('/');
        }
    }

    //Route::get('/teachers/create', [\App\Http\Controllers\TeachersController::class, 'create']);
    public function create(){
        if(Gate::allows('admin')){
            $roles = config('enums.roles');
            $users = User::where('role', '!=' , $roles['TEACHER'])->get();

            return view('/teachers/create', ['users' => $users]);
        }else{
            return redirect('/');
        }
    }

    //Route::post('/teachers', [\App\Http\Controllers\TeachersController::class, 'store']);
    public function store(Request $request){
        if(Gate::allows('admin')){
            //add data to teachers table
            $teacher = new Teacher();
            $data = $this->validateData($request);

            $teacher->degree = $data['degree'];
            $teacher->user_id = $data['user_id'];
            $teacher->department = $data['department'];

            $teacher->save();

            //change role of the user
            $roles = config('enums.roles');
            $teacher->user->role = $roles['TEACHER'];
            $teacher->user->save();

            return redirect('/teachers');
        }else{
            return redirect('/');
        }
    }

    //Route::get('/teachers/{id}/edit', [\App\Http\Controllers\TeachersController::class, 'edit']);
    public function edit($id){
        if(Gate::allows('admin')){
            $teacher = Teacher::find($id);
            $users = User::all()->sortBy('name');

            return view('teachers/edit', ['teacher' => $teacher, 'users' => $users]);
        }else{
            return redirect('/');
        }
    }

    //Route::patch('/teachers/{id}', [\App\Http\Controllers\TeachersController::class, 'update']);
    public function update($id){
        if(Gate::allows('admin')){
            $teacher = Teacher::find($id);
            $data = $this->validateData(\request());
    
            $teacher->degree = $data['degree'];
            $teacher->user_id = $data['user_id'];
            $teacher->department = $data['department'];
    
            $teacher->save();
    
            return redirect('/teachers');
        }else{
            return redirect('/');
        }
    }

    //Route::delete('/teachers/{id}', [\App\Http\Controllers\TeachersController::class, 'destroy']);
    public function destroy($id){
        if(Gate::allows('admin')){
            //delete data from teachers table 
            $teacher = Teacher::find($id);
            $teacher->delete();

            //change role of the user
            $roles = config('enums.roles');
            $teacher->user->role = $roles['STUDENT'];
            $teacher->user->save();
    
            return redirect('/teachers');
        }else{
            return redirect('/');
        }
    }
    
    //VALIDATE
    private function validateData($data){
        return $this->validate($data, [
            'degree' => ['required', 'min:3'],
            'user_id' => ['required'],
            'department' => ['required'],
        ], [
            'degree.required' => 'Назва вченого звання має бути заповнена!',
            'degree.min' => 'Назва вченого звання має бути довше 3 символів!',
            'department.required' => 'Кафедра має бути обрана!',
            'user_id.required' => 'Відповідний обліковий запис має бути обраний!',
        ]);
    }
}
