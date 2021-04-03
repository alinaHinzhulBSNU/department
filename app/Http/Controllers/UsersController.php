<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class UsersController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    //Route::get('/users', [\App\Http\Controllers\UsersController::class, 'index']);
    public function index(){
        if(Gate::allows('admin')){
            $users = User::all()->sortBy('name');
            return view('users/index', ['users' => $users]);
        }else{
            return redirect('/');
        }
    }

    //Route::get('/users/{id}/edit', [\App\Http\Controllers\UsersController::class, 'edit']);
    public function edit($id){
        if(Gate::allows('admin')){
            $user = User::find($id);
            return view('users/edit', ['user' => $user]);
        }else{
            return redirect('/');
        }
    }

    //Route::patch('/users/{id}', [\App\Http\Controllers\UsersController::class, 'update']);
    public function update($id){
        if(Gate::allows('admin')){
            $user = User::find($id);
            $data = $this->validateData(\request());
    
            $user->name = $data['name'];
            $user->role = $data['role'];
    
            $user->save();
    
            return redirect('/users');
        }else{
            return redirect('/');
        }
    }

    //Route::delete('/users/{id}', [\App\Http\Controllers\UsersController::class, 'destroy']);
    public function destroy($id){
        if(Gate::allows('admin')){
            $user = User::find($id);
            $user->delete();
    
            return redirect('/users');
        }else{
            return redirect('/');
        }
    }

    //SEARCH
    public function search(){
        if(Gate::allows('admin')){
            $users = User::all();
            $name =  \request()->input('name');

            if($name){
                $found_users = array();

                foreach ($users as $user){
                    if(mb_stristr($user->name, $name, false, 'UTF-8') !== false){
                        array_push($found_users, $user);
                    }
                }

                return view('users/index', ['users' => $found_users]);
            }

            return view('users/index', ['users' => $users]);
        }else{
            return redirect('/');
        }
    }

    //VALIDATE
    private function validateData($data){
        return $this->validate($data, [
            'name' => ['required', 'min:3'],
            'role' => ['required'],
        ], [
            'name.required' => 'ПІБ має бути заповнене!',
            'name.min' => 'ПІБ має бути довше 3 символів!',
            'role.required' => 'Роль має бути обрана!',
        ]);
    }
}
