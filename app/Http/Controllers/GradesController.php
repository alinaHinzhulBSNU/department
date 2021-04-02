<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\Group;
use App\Models\Grade;
use App\Models\Teacher;
use App\Models\Subject;

use DB; 
use PDF; //alias of the plugin class we've installed 

class GradesController extends Controller
{
    private $group;// група, журнал якої переглядають

    private $subject; // subject to load PDF for 

    public function __construct(){
        $this->middleware('auth');
        $this->group = Group::find(\request()->route('group_id'));
    }

    public function index(){
        $subjects = $this->subjects();
        //dd($subjects); 
        return view('grades/index', ['group' => $this->group, 'subjects' => $subjects]);
    }

    // --------------------------- PDF functionality --------------------------
    function pdf(){
        $pdf = \App::make('dompdf.wrapper'); 
        $pdf->loadHTML($this->convertGradesToHtmlAllSubjects());
        //loadHTML is the func that converts html data to pdf  
        return $pdf->stream();
        //stream func allows to show pdf file in browser 
    }

    function pdfOneSubject(){

        $this->subject = Subject::find(\request()->route('subject_id'));
        $pdf = \App::make('dompdf.wrapper'); 
        $pdf->loadHTML($this->convertGradesToHtmlCurrentSubject());
        return $pdf->stream(); 
    }

    function convertGradesToHtmlCurrentSubject(){
        //to use another font, we have to fix the encoding! 
        $output = '
        <head>
            <style>
                body { 
                    font-family: DejaVu Sans 
                }
    
                .footer {
                    width: 100%;
                    text-align: right;
                    position: fixed;
                    bottom: 15px;
                    right: 20px; 
                }
                .pagenum:before {
                    content: counter(page); 
                    
                }
                table {
                    width: 97%; 
                    border-collapse: collapse; 
                    border: 0px;
                }
    
                .page-break {
                    page-break-before: always;
                    }
                @page { margin: 20px 30px 40px 50px; }
    
                th {
                    border: 1px solid; 
                    padding:12px;
                    width="30%; 
                }
    
                td {
                    border: 1px solid; 
                    padding: 12px;
                    text-align: center; 
                    width: 30%; 
                }
            </style> 
        </head> 

        <body>
        <h3 align="center">Факультет Комп\'ютерних Наук</h3>
        <h3 align="center">Група '.$this->group->number.'</h3>
        <table>
        <tr>
            <th>Студент</th>
            <th><p>'.$this->subject->name.'</p></th>
        </tr>'; 
        //table rows counter:
        $c = 3; //so that first page has fewer rows than the rest
        //for ($x = 0; $x <= 14; $x++) {  //for testing pagination 
            foreach($this->group->students as $student ){ 
                $c++; 
                $output .= '
                <tr>
                    <td>
                    '.$student->user->name .'
                    </td>'; 

                    //open cell 
                    $output .= '<td>'; 
                    foreach($student->grades as $grade){
                        if($grade->subject->id === $this->subject->id){
                        $output .= $grade->grade; 
                        
                        }
                    }    
                    $output .= '</td>' ;  //if there is a grade, fill it in, if there isn't - leave blank         

                $output .= '
                </tr>
                '; 

                if($c == 19){ 
                    $output .= '
                        </table> 
                            <div class="footer" style="margin-bottom: 20px;">
                                <span class="pagenum"></span>
                            </div>
                        <div class="page-break"></div>
                        <table> 
                        <tr>
                        <th>Студент</th>'; 
            
                    $output .= '<th>'.$this->subject->name.'</th>'; 
                     
                    $output .= '</tr>'; 
                    $c=0; 
                }

            }
        //}//regular for 
        $output .= '
        </table> 
            <div class="footer" style="margin-bottom: 20px;">
                <span class="pagenum"></span>
            </div>
        </body>'; 
        return $output; 

    }

    //for ALL subjects:   
    function convertGradesToHtmlAllSubjects(){
        $subjects = $this->subjects();
        // would be good to make the subject names display vertically 
        $output = '
        <head>
        <style>
            body { 
                font-family: DejaVu Sans 
            }

            .footer {
                width: 100%;
                text-align: right;
                position: fixed;
                bottom: 15px;
                right: 20px; 
            }
            .pagenum:before {
                content: counter(page); 
                
            }
            table {
                width: 97%; 
                border-collapse: collapse; 
                border: 0px;
            }

            .page-break {
                page-break-before: always;
                }
            @page { margin: 20px 30px 40px 50px; }

            th {
                border: 1px solid; 
                padding:12px;
                width="30%; 
            }

            td {
                border: 1px solid; 
                padding: 12px;
                text-align: center; 
                width: 30%; 
            }
        </style>
        </head> 
        <body>

        <h3 align="center">Факультет Комп\'ютерних Наук</h3>
        <h3 align="center">Група '.$this->group->number.'</h3>
        <table>
        <tr>
            <th>Студент</th>'; 

        foreach($subjects as $subject ){
            $output .= '<th>'.$subject->name.'</th>'; 
        }   
        $output .= '</tr>'; 

        $c = 2;
        //for ($x = 0; $x <= 14; $x++) { 
            foreach($this->group->students as $student ){ 
            
                $c++; 
                $output .= '
                <tr>
                    <td>
                    '.$student->user->name .'
                    </td>'; 

                    foreach($subjects as $subject ){
                        $output .= '<td>
                            '; 
                        foreach($student->grades as $grade){
                            if($grade->subject->id === $subject->id){
                            $output .= $grade->grade; 
                            }
                        } 
                        $output .= '</td>' ;
                    } 

                $output .= '
                </tr>
                ';
    
                if($c == 15){
                    $output .= '
                        </table> 
                            <div class="footer" style="margin-bottom: 20px;">
                                <span class="pagenum"></span>
                            </div>
                        <div class="page-break"></div>
                        <table> 
                        <tr>
                        <th>Студент</th>'; 

                    foreach($subjects as $subject ){
                        $output .= '<th>'.$subject->name.'</th>'; 
                    }   
                    $output .= '</tr>'; 
                    $c=0; 
                }
            
            }
        //} //regular for
        $output.='</table> 
            <div class="footer" style="margin-bottom: 20px;">
                <span class="pagenum"></span>
            </div>
       </body> 
        '; 

        return $output; 
    }

    // --------------------------- end of PDF functionality --------------------------



    public function create(){
        // лише викладачі можуть виставляти оцінки 
        if(Gate::allows('teach')){
            $subjects = Auth::user()->teacher->subjects;
            return view('grades/create', ['group' => $this->group, 'subjects' => $subjects]);
        }

        return redirect('/grades/'.$this->group->id);
    }

    public function store(){
        if(Gate::allows('teach')){
            $grade = new Grade();
            $data = $this->validateData(\request());

            // не виставляти дві або більше оцінки одному студенту з тої самої дисципліни
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

    public function edit(){
        $grade = Grade::find(\request()->route('id'));

        // виставити оцінку з певної дисципліни може лише той викладач, що її веде (див. GradePolicy)
        if(Auth::user()->can('update', $grade, Grade::class)) {
            return view('grades/edit', ['grade' => $grade, 'group' => $this->group]);
        }

        return redirect('/grades/'.$this->group->id);
    }

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

    public function destroy(){
        $grade = Grade::find(\request()->route('id'));

        if(Auth::user()->can('update', $grade, Grade::class)){
            $grade->delete();
        }

        return redirect('/grades/'.$this->group->id);
    }
    
    //VALIDATE
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

    //GET ALL SUBJECTS
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
}