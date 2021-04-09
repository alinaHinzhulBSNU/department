<?php
/**
 * Файл з контролером для даних про студентів
 * 
 * @author Olena Groza
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\User; 
use App\Models\Group; 
use App\Models\Student; 

/**
 * Контролер для даних про студентів
 */
class StudentsController extends Controller
{
   
    /**
     * Створення нового екземпляру StudentsController
     * 
     * Перевірка авторизації
     * 
     * @return void
     */
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Перегляд списку всіх студентів
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //are visible to all? (if not - Add Gate!)
        $students = Student::all()->sortBy('group_id'); //sort by what? 
        $users = User::all(); 
        return view('students/index', ['students' => $students, 'users' => $users]); 
    }

    /**
     * Перехід на сторінку створення даних про студента
     * 
     * Лише адміністратор може перейти на цю сторінку.
     * Запис про студента можна створити на базі того облікового запису, що ще не належить студенту.
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        if(Gate::allows('admin')){
            $roles = config('enums.roles'); 
            $users = User::where('role', $roles['NONE'])->get(); //has not role already  
            $groups = Group::all(); 
            return view('students/create', ['users' => $users, 'groups' => $groups]); 
        }else{
            return redirect('/'); 
        }
    }

    /**
     * Збереження даних про студента
     * 
     * Лише адміністратор може зберегти дані про студента.
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Contracts\Support\Renderable
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
     * Перейти на сторінку редагування даних про студента
     * 
     * Відредагувати дані про студента може лише адміністратор.
     * 
     * @param mixed $id
     * 
     * @return \Illuminate\Contracts\Support\Renderable
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
     * Збереження відредагованих даних про студентів
     * 
     * Зберігати відредаговані дані може лише адміністратор.
     * 
     * @param mixed $id
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
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

    /**
     * Видалення даних про студента
     * 
     * Видаляти дані про студента може лише адміністратор.
     * При видаленні роль student змінюється на none.
     * 
     * @param mixed $id
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function destroy($id)
    {
        if(Gate::allows('admin')){
            //delete data from students table 
            $student = Student::find($id);
            $student->delete();
            
            //change role of the user
            $roles = config('enums.roles');
             $student->user->role = $roles['NONE']; //set role to none if one stops being a student 
            $student->user->save();
    
            return redirect('/students');
        }else{
            return redirect('/');
        }
    }

    /**
     * Пошук даних про студента за ПІБ
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function search(){
        $students = Student::all();
        $name =  \request()->input('name');

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
    }

    /**
     * Валідація даних про студента, отриманих з форм редагування та додавання
     * 
     * @param mixed $data
     * 
     * @return mixed
     */
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
}
