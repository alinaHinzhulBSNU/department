<?php
/**
 * Файл з контролером для даних про студентські групи
 * 
 * @author Alina Hinzhul
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Group;

/**
 * Контролер для даних про студентські групи
 */
class GroupsController extends Controller
{
    /**
     * Створення нового екземпляру GroupsController
     * 
     * Перевірка авторизації.
     * 
     * @return void
     */
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Перегляд списку студентських груп
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        $groups = Group::all()->sortBy('number');
        return view('groups/index', ['groups' => $groups]);
    }

    /**
     * Перехід на форму створення студентської групи
     * 
     * Лише адміністратор може створити дані про студентську групу.
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(){
        if(Gate::allows('admin')){
            return view('groups/create');
        }else{
            return redirect('/groups');
        }
    }

    /**
     * Збереження створеної групи
     * 
     * Лише адміністратор може зберегти дані про створену групу.
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
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

    /**
     * Перехід на форму редагування групи
     * 
     * Перейти на форму редагування може лише адміністратор.
     * 
     * @param mixed $id
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id){
        if(Gate::allows('admin')){
            $group = Group::find($id);
            return view('groups/edit', ['group' =>  $group]);
        }else{
            return redirect('/groups');
        }
    }

    /**
     * Збереження відредагованих даних про групу
     * 
     * Зберегти відредаговані дані може лише адміністратор.
     * 
     * @param mixed $id
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
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

    /**
     * Видалення даних про групу
     * 
     * Видалити дані про групу може лише адміністратор.
     * 
     * @param mixed $id
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function destroy($id){
        if(Gate::allows('admin')){
            $group = Group::find($id);
            $group->delete();
        }

        return redirect('/groups');
    }

    /**
     * Пошук групи
     * 
     * Пошук може відбуватися як за курсом і спеціальністю, так і за кожним з цих параметрів окремо.
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
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
    /**
     * Валідація створених даних про студентську групу
     * 
     * @param mixed $data
     * 
     * @return mixed
     */
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

    /**
     * Валідація відредагованих даних про студентську групу
     * 
     * Інші правила валідації при редагуванні даних.
     * 
     * @param mixed $data
     * 
     * @return mixed
     */
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
