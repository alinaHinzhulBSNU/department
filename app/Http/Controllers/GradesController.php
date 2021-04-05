<?php
/**
 * Файл з контролером для даних про журнал оцінок
 * 
 * @author Alina Hinzhul, Olena Groza
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\Group;
use App\Models\Grade;
use App\Models\Teacher;
use App\Models\Subject;

/**
 * Alias of the plugin class we've installed (DomPdf)
 */
use PDF;  

/**
 * Контролер для даних про оцінки
 */
class GradesController extends Controller
{
    /**
     * Студентська група, журнал якої переглядаємо
     * 
     * @var Group $group
     */
    private $group;

    /**
     * Subject to load PDF for
     *  
     * @var Subject $subject
     */
    private $subject;

    /**
     * Створення нового екземпляру GradesController
     * 
     * Перевірка авторизації.
     * 
     * Отримання групи, id якої вказано в запиті.
     * 
     * @return void
     */
    public function __construct(){
        $this->middleware('auth');
        $this->group = Group::find(\request()->route('group_id'));
    }

    /**
     * Перегляд журналу
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        $subjects = $this->subjects();
        return view('grades/index', ['group' => $this->group, 'subjects' => $subjects]);
    }

    /**
     * Перехід на форму створення оцінки
     * 
     * Ставити оцінки можуть лише викладачі.
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(){
        if(Gate::allows('teach')){
            $subjects = Auth::user()->teacher->subjects;
            return view('grades/create', ['group' => $this->group, 'subjects' => $subjects]);
        }

        return redirect('/grades/'.$this->group->id);
    }

    /**
     * Збереження виставленої оцінки
     * 
     * Не можна виставити дві або більше оцінки з однієї дисципліни одному студенту.
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(){
        if(Gate::allows('teach')){
            $grade = new Grade();
            $data = $this->validateData(\request());

            $is_grade_unique = Grade::where('student_id', $data['student_id'])
                                    ->where('subject_id', $data['subject_id'])
                                    ->exists();

            if(!$is_grade_unique){
                $grade->student_id = $data['student_id'];
                $grade->subject_id = $data['subject_id'];
                $grade->semester = $data['semester'];
                $grade->grade = $data['grade'];
    
                $grade->save();
            }
        }

        return redirect('/grades/'.$this->group->id);
    }

    /**
     * Перехід на форму редагування оцінки
     * 
     * Виставити оцінку з певної дисципліни може лише той викладач, що її веде.
     * Дана логіка описана в GradePolicy.
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(){
        $grade = Grade::find(\request()->route('id'));

        if(Auth::user()->can('update', $grade, Grade::class)) {
            return view('grades/edit', ['grade' => $grade, 'group' => $this->group]);
        }

        return redirect('/grades/'.$this->group->id);
    }

    /**
     * Збереження відредагованої оцінки
     * 
     * Відредагувати оцінку може лише той викладач, що веде вказану дисципліну.
     * Дана логіка описана в GradePolicy.
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(){
        $grade = Grade::find(\request()->route('id'));

        if(Auth::user()->can('update', $grade, Grade::class)) {
            $data = $this->validateData(\request());

            $grade->student_id = $data['student_id'];
            $grade->subject_id = $data['subject_id'];
            $grade->semester = $data['semester'];
            $grade->grade = $data['grade'];

            $grade->save();
        }

        return redirect('/grades/'.$this->group->id);
    }

    /**
     * Видалення виставленої оцінки
     * 
     * Видалити оцінку може лише той викладач, що веде вказану дисципліну.
     * Дана логіка описана в GradePolicy.
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function destroy(){
        $grade = Grade::find(\request()->route('id'));

        if(Auth::user()->can('update', $grade, Grade::class)){
            $grade->delete();
        }

        return redirect('/grades/'.$this->group->id);
    }

    /**
     * Отримання всіх дисциплін
     * 
     * Всі дисципліни, що були у студентів даної групи.
     * 
     * @return array $subjects
     */
    private function subjects(){
        $grades = Grade::all();
        $subjects = array();

        foreach($this->group->students as $student){
            foreach($grades as $grade){
                if($grade->student->id === $student->id){
                    $subjects[] = $grade->subject;
                }
            }
        }

        $subjects = array_unique($subjects);

        return $subjects;
    }


    // --------------------------- PDF functionality --------------------------
    /**
     * Створення відомості по всім дисциплінам
     * 
     * Створення PDF документу, що містить дані про оцінки з усіх дисциплін.
     * Може завантажити лише викладач.
     * 
     * @return PDF
     */
    function pdfManySubjects(){
        $pdf = PDF::loadView('grades/pdf_many', [
            'group' => $this->group,
            'subjects' => $this->subjects(),
        ]);
 
        return $pdf->stream();
        
    }

    /**
     * Створення відомості з однієї дисципліни
     * 
     * Створення PDF документу, що містить дані про оцінки з однієї дисципліни.
     * Може завантажити лише викладач.
     * 
     * @return PDF
     */
    function pdfOneSubject(){
        $this->subject = Subject::find(\request()->route('subject_id'));

        $pdf = PDF::loadView('grades/pdf_one', [
            'group' => $this->group,
            'subject' => $this->subject
        ]);

        return $pdf->stream(); 
    }
    // --------------------------- end of PDF functionality --------------------------
    

    /**
     * Валідація даних про оцінки, які отримані з форм редагування та додавання
     * 
     * Використовується як для створених, так і для відредагованих даних.
     * 
     * @param mixed $data
     * 
     * @return mixed
     */
    private function validateData($data){ 
        return $this->validate($data, [
            'subject_id' => ['required'],
            'student_id' => ['required'],
            'grade' => ['required', 'integer', 'min:0', 'max:100'],
            'semester' => ['required', 'integer', 'min:1', 'max:12'],
        ], [
            'subject_id.required' => 'Дисципліна має бути обрана!',
            'student_id.required' => 'Студент має бути обраний!',
            'grade.required' => 'Оцінка має бути виставлена!',
            'grade.integer' => 'Оцінка має бути цілим числом!',
            'grade.min' => 'Оцінка не може бути менше 0!',
            'grade.max' => 'Оцінка не може бути більше 100!',
            'semester.required' => 'Семестр має бути записаний!',
            'semester.integer' => 'Семестр має бути цілим числом!',
            'semester.min' => 'Семестр не може бути менше 1!',
            'semester.max' => 'Семестр не може бути більше 12!',
        ]);
    }
}