<?php
/**
 * Файл з контролером для даних про викладачів
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Teacher;
use App\Models\User;

/**
 * Контролер для даних про викладачів
 */
class TeachersController extends Controller
{
    /**
     * Створення нового екземпляру TeachersController
     * 
     * Перевірка авторизації
     * 
     * @return void
     */
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Перегляд списку всіх викладачів
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        if(Gate::allows('admin')){
            $teachers = Teacher::all()->sortBy('department');
            return view('teachers/index', ['teachers' => $teachers]);
        }else{
            return redirect('/');
        }
    }

    /**
     * Перехід на сторінку створення даних про викладача
     * 
     * Створювати дані про викладача може лише адміністратор.
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(){
        if(Gate::allows('admin')){
            $roles = config('enums.roles');
            $users = User::where('role', '!=' , $roles['TEACHER'])->get();

            return view('/teachers/create', ['users' => $users]);
        }else{
            return redirect('/');
        }
    }

    /**
     * Збереження даних про викладача
     * 
     * Зберігати дані про викладача може лише адміністратор.
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
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

    /**
     * Перехід на форму редагування даних про викладача
     * 
     * Редагувати дані про викладача може лише адміністратор.
     * 
     * @param mixed $id
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id){
        if(Gate::allows('admin')){
            $teacher = Teacher::find($id);
            $users = User::all()->sortBy('name');

            return view('teachers/edit', ['teacher' => $teacher, 'users' => $users]);
        }else{
            return redirect('/');
        }
    }

    /**
     * Збереження відредагованих даних про викладача
     * 
     * Зберігати відредаговані дані про викладача може лише адміністратор.
     * 
     * @param mixed $id
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
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

    /**
     * Видалення даних про викладача
     * 
     * Лише адміністратор може видалити дані про викладача.
     *  
     * @param mixed $id
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function destroy($id){
        if(Gate::allows('admin')){
            //delete data from teachers table 
            $teacher = Teacher::find($id);
            $teacher->delete();

            //change role of the user to NONE 
            $roles = config('enums.roles');
            $teacher->user->role = $roles['NONE']; 
            $teacher->user->save();
    
            return redirect('/teachers');
        }else{
            return redirect('/');
        }
    }
    
    /**
     * Валідація даних про викладача, отриманих з форм редагування та додавання
     * 
     * @param mixed $data
     * 
     * @return mixed
     */
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
