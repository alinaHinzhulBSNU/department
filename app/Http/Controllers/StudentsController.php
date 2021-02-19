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
            $roles = config('enums.roles'); //not quite sure how this works 
            $users = User::where('role', '!=', $roles['STUDENT'])->get(); //?? 
            $groups = Group::all(); 
            return view('students/create', ['users' => $users, 'groups' => $groups]); 
        }else{
            return redirect('/students'); 
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
            //add data to teachers table
            $student = new Student();
            //$data = $this->validateData($request);
            $data = $request; 
           //TODO: need to translate checkbox values (on, off) into 0 and 1 before inserting into DB!
            $student->user_id = $data['user_id'];
            $student->group_id = $data['group_id']; 
            $student->is_class_leader = $data['is_class_leader'] == "on" ? 1 : 0;
            $student->has_grant = $data['has_grant']== "on" ? 1 : 0;
            $student->has_social_grant = $data['has_social_grant']== "on" ? 1 : 0;

            $student->save();

            //change role of the user
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
