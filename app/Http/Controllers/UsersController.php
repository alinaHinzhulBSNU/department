<?php
/**
 * Файл з контролером для даних про користувачів
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

/**
 * Контролер для даних про користувачів
 */
class UsersController extends Controller
{
    /**
     * Створення нового екземпляру UsersController
     * 
     * Перевірка авторизації
     * 
     * @return void
     */
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Перегляд списку користувачів
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        if(Gate::allows('admin')){
            $users = User::all()->sortBy('name');
            return view('users/index', ['users' => $users]);
        }else{
            return redirect('/');
        }
    }

    /**
     * Перехід на форму редагування користувача
     * 
     * Перейти на форму редагування користувача може лише адміністратор.
     * 
     * @param mixed $id
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id){
        if(Gate::allows('admin')){
            $user = User::find($id);
            return view('users/edit', ['user' => $user]);
        }else{
            return redirect('/');
        }
    }

    /**
     * Збереження відредагованих даних про користувача
     * 
     * Зберігати відредаговані дані про користувача може лише адміністратор.
     * 
     * @param mixed $id
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update($id){
        if(Gate::allows('admin')){
            $user = User::find($id);
            $data = $this->validateData(\request());
    
            $user->name = $data['name'];
    
            $user->save();
    
            return redirect('/users');
        }else{
            return redirect('/');
        }
    }

    /**
     * Видалення даних про користувача
     * 
     * Видаляти дані про користувача може лише адміністратор.
     * 
     * @param mixed $id
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function destroy($id){
        if(Gate::allows('admin')){
            $user = User::find($id);
            $user->delete();
    
            return redirect('/users');
        }else{
            return redirect('/');
        }
    }

    /**
     * Пошук користувача за ПІБ
     * 
     * Шукати користувачів за ПІБ може лише адміністратор.
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
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

    /**
     * Валідація даних про користувачів, отриманих з форм редагування та додавання
     * 
     * @param mixed $data
     * 
     * @return mixed
     */
    private function validateData($data){
        return $this->validate($data, [
            'name' => ['required', 'min:3'],
        ], [
            'name.required' => 'ПІБ має бути заповнене!',
            'name.min' => 'ПІБ має бути довше 3 символів!',
        ]);
    }
}
