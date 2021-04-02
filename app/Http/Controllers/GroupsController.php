<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Group;

class GroupsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    //Route::get('/groups', [\App\Http\Controllers\GroupsController::class, 'index']);
    public function index(){
        $groups = Group::all()->sortBy('number');
        return view('groups/index', ['groups' => $groups]);
    }

    //Route::get('/groups/create', [\App\Http\Controllers\GroupsController::class, 'create']);
    public function create(){
        if(Gate::allows('admin')){
            return view('groups/create');
        }else{
            return redirect('/groups');
        }
    }

    //Route::post('/groups', [\App\Http\Controllers\GroupsController::class, 'store']);
    public function store(Request $request){
        if(Gate::allows('admin')){
            $group = new Group();
            $data = $this->validateCreatedData($request);
    
            $group->number = $data['number'];
            $group->course = $data['course'];
            $group->major = $data['major'];
            $group->start_year = $data['start_year'];
            $group->end_year = $data['end_year'];

            $group->save();

        }

        return redirect('/groups');
    }

    //Route::get('/groups/{id}/edit', [\App\Http\Controllers\GroupsController::class, 'edit']);
    public function edit($id){
        if(Gate::allows('admin')){
            $group = Group::find($id);
            return view('groups/edit', ['group' =>  $group]);
        }else{
            return redirect('/groups');
        }
    }

    //Route::patch('/groups/{id}', [\App\Http\Controllers\GroupsController::class, 'update']);
    public function update($id){
        if(Gate::allows('admin')){
            $group = Group::find($id);
            $data = $this->validateUpdatedData(\request());
    
            $group->course = $data['course'];
            $group->major = $data['major'];
            $group->start_year = $data['start_year'];
            $group->end_year = $data['end_year'];
    
            $group->save();
        }

        return redirect('/groups');
    }

    //Route::delete('/groups/{id}', [\App\Http\Controllers\GroupsController::class, 'destroy']);
    public function destroy($id){
        if(Gate::allows('admin')){
            $group = Group::find($id);
            $group->delete();
        }

        return redirect('/groups');
    }

    //SEARCH
    public function search(){
        $groups = Group::all()->sortBy('number');
        $major =  \request()->input('major');
        $course = \request()->input('course');

        $found_groups = array();

        // Якщо обрали і курс, і спеціальність
        if($major and $course){
            foreach ($groups as $group){
                if($group->major === $major and $group->course == $course){
                    array_push($found_groups, $group);
                }
            }

            return view('groups/index', ['groups' => $found_groups]);
        }

        // Якщо обрали лише спеціальність
        if($major){
            foreach ($groups as $group){
                if($group->major === $major){
                    array_push($found_groups, $group);
                }
            }

            return view('groups/index', ['groups' => $found_groups]);
        }

        // Якщо обрали лише курс
        if($course){
            foreach ($groups as $group){
                if($group->course == $course){
                    array_push($found_groups, $group);
                }
            }

            return view('groups/index', ['groups' => $found_groups]);
        }

        return view('groups/index', ['groups' => $groups]);
    }

    //VALIDATE
    private function validateCreatedData($data){
        return $this->validate($data, [
            'number' => ['required', 'min:3', 'unique:groups'],
            'course' => ['required', 'integer', 'max:6'],
            'major' => ['required'],
            'start_year' => ['required', 'integer'],
            'end_year' => ['required', 'integer'],
        ], [
            'number.required' => 'Номер групи має бути заповнений!',
            'number.min' => 'Номер групи має складатися з 3 або більшої кількості символів!',
            'number.unique' => 'Група з таким номером вже існує!', 
            'course.required' => 'Номер курсу має бути заповнений!',
            'course.integer' => 'Номер курсу - це ціле число!',
            'course.max' => 'Номер курсу не може бути більше 6!',
            'major.required' => 'Назва спеціальності має бути заповнена!',
            'start_year.required' => 'Початок академічного року має бути заповнений!',
            'start_year.integer' => 'Початок академічного року - це ціле число!',
            'end_year.required' => 'Кінець академічного року має бути заповнений!',
            'end_year.integer' => 'Кінець академічного року - це ціле число!',
        ]);
    }

    // Інші правила валідації при редагуванні
    private function validateUpdatedData($data){
        return $this->validate($data, [
            'course' => ['required', 'integer', 'max:6'],
            'major' => ['required'],
            'start_year' => ['required', 'integer'],
            'end_year' => ['required', 'integer'],
        ], [
            'course.required' => 'Номер курсу має бути заповнений!',
            'course.integer' => 'Номер курсу - це ціле число!',
            'course.max' => 'Номер курсу не може бути більше 6!',
            'major.required' => 'Назва спеціальності має бути заповнена!',
            'start_year.required' => 'Початок академічного року має бути заповнений!',
            'start_year.integer' => 'Початок академічного року - це ціле число!',
            'end_year.required' => 'Кінець академічного року має бути заповнений!',
            'end_year.integer' => 'Кінець академічного року - це ціле число!',
        ]);
    }
}
