<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\User; 
use App\Models\Group; 
use App\Models\Student; 

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        //are visible to all? (if not - Add Gate!)
        $students = Student::all()->sortBy('group_id'); //sort by what? 
        $users = User::all(); 
        return view('students/index', ['students' => $students, 'users' => $users]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(Gate::allows('admin')){
            $student = Student::find($id);
     
            $data = $this->validateData(\request());

            $student->group_id = $data['group_id']; 
            $student->is_class_leader = $data['is_class_leader']; 
            $student->has_grant = $data['has_grant']; 
            $student->has_social_grant = $data['has_social_grant']; 

            $student->save();
    
            return redirect('/students');
        }else{
            return redirect('/');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

        ]);
    }
}
