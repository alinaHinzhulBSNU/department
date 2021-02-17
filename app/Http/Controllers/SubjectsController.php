<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Subject;
use App\Models\Teacher;

class SubjectsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    //Route::get('/subjects', [\App\Http\Controllers\SubjectsController::class, 'index']);
    public function index(){
        if(Gate::allows('admin')){
            $subjects = Subject::all()->sortBy('name');
            return view('subjects/index', ['subjects' => $subjects]);
        }else{
            return redirect('/');
        }
    }

    //Route::get('/subjects/create', [\App\Http\Controllers\SubjectsController::class, 'create']);
    public function create(){
        if(Gate::allows('admin')){
            $teachers = Teacher::all()->sortBy('degree');
            return view('/subjects/create', ['teachers' => $teachers]);
        }else{
            return redirect('/');
        }
    }

    //Route::post('/subjects', [\App\Http\Controllers\SubjectsController::class, 'store']);
    public function store(Request $request){
        if(Gate::allows('admin')){
            $subject = new Subject();
            $data = $this->validateData($request);

            $subject->name = $data['name'];
            $subject->exam_type = $data['exam_type'];
            $subject->description = $data['description'];
            $subject->credit = $data['credit'];
            $subject->teacher_id = $data['teacher_id'];

            $subject->save();

            return redirect('/subjects');
        }else{
            return redirect('/');
        }
    }

    //Route::get('/subjects/{id}/edit', [\App\Http\Controllers\SubjectsController::class, 'edit']);
    public function edit($id){
        if(Gate::allows('admin')){
            $subject = Subject::find($id);
            $teachers = Teacher::all()->sortBy('department');

            return view('subjects/edit', ['subject' =>  $subject, 'teachers' => $teachers]);
        }else{
            return redirect('/');
        }
    }

    //Route::patch('/subjects/{id}', [\App\Http\Controllers\SubjectsController::class, 'update']);
    public function update($id){
        if(Gate::allows('admin')){
            $subject = Subject::find($id);
            $data = $this->validateData(\request());
    
            $subject->name = $data['name'];
            $subject->exam_type = $data['exam_type'];
            $subject->description = $data['description'];
            $subject->credit = $data['credit'];
            $subject->teacher_id = $data['teacher_id'];
    
            $subject->save();
    
            return redirect('/subjects');
        }else{
            return redirect('/');
        }
    }

    //Route::delete('/subjects/{id}', [\App\Http\Controllers\SubjectsController::class, 'destroy']);
    public function destroy($id){
        if(Gate::allows('admin')){
            $subject = Subject::find($id);
            $subject->delete();

            return redirect('/subjects');
        }else{
            return redirect('/');
        }
    }
    
    //VALIDATE
    private function validateData($data){
        return $this->validate($data, [
            'name' => ['required', 'min:3'],
            'exam_type' => ['required'],
            'description' => ['required', 'min:5'],
            'credit' => ['required'],
            'teacher_id' => ['required'],
        ], [
            'name.required' => 'Назва дисципліни має бути заповнена!',
            'name.min' => 'Назва дисципліни має бути довше 3 символів!',
            'description.required' => 'Опис дисципліни має бути заповнена!',
            'description.min' => 'Опис дисципліни має бути довше 5 символів!',
            'exam_type.required' => 'Тип підсумкової атестації має бути обраний!',
            'credit.required' => 'Кредит дисципліни має бути заповнений!',
            'teacher_id.required' => 'Викладач має бути обраний!',
        ]);
    }
}
