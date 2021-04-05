<?php
/**
 * Файл з контролером для даних про дисципліни
 * 
 * @author Alina Hinzhul
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Subject;
use App\Models\Teacher;

/**
 * Контролер для даних про дисципліни
 */
class SubjectsController extends Controller
{
    /**
     * Створення нового екземпляру SubjectsController
     * 
     * Перевірка авторизації.
     * 
     * @return void
     */
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Перегляд списку всіх дисциплін
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        if(Gate::allows('admin')){
            $subjects = Subject::all()->sortBy('name');
            return view('subjects/index', ['subjects' => $subjects]);
        }else{
            return redirect('/');
        }
    }

    /**
     * Перехід на форму створення даних про дисципліну
     * 
     * Лише адміністратор може додавати дані про дисципліну.
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(){
        if(Gate::allows('admin')){
            $teachers = Teacher::all()->sortBy('degree');
            return view('/subjects/create', ['teachers' => $teachers]);
        }else{
            return redirect('/');
        }
    }

    /**
     * Збереження даних про дисципліну
     * 
     * Лише адміністратор може зберігати дані про дисципліну.
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
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

    /**
     * Перехід на форму редагування дисципліни
     * 
     * Лише адміністратор може переходити на форму редагування дисципліни.
     * 
     * @param mixed $id
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id){
        if(Gate::allows('admin')){
            $subject = Subject::find($id);
            $teachers = Teacher::all()->sortBy('department');

            return view('subjects/edit', ['subject' =>  $subject, 'teachers' => $teachers]);
        }else{
            return redirect('/');
        }
    }

    /**
     * Збереження відредагованих даних про дисципліну
     * 
     * Лише адміністратор може зберігати дані про дисципліну.
     * 
     * @param mixed $id
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
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

    /**
     * Видалення даних про дисципліну
     * 
     * Лише адміністратор може видаляти дані про дисципліну.
     * 
     * @param mixed $id
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function destroy($id){
        if(Gate::allows('admin')){
            $subject = Subject::find($id);
            $subject->delete();

            return redirect('/subjects');
        }else{
            return redirect('/');
        }
    }
    
    /**
     * Валідація даних про дисципліну, отриманих з форм редагування та додавання
     * 
     * @param mixed $data
     * 
     * @return mixed
     */
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
