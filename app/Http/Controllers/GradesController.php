<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\Group;
use App\Models\Grade;
use App\Models\Teacher;
use App\Models\Subject;

use DB; //predefined class (seemingly)
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
    // we get group and subjects data in array. We can get the grade though those two somehow, right? 
    //  group->student->grade (->subjectID)
    // we have already sent all of the data to the view, so no need to worry about that 
    // the route is ready as well!

    function pdf(){
        $pdf = \App::make('dompdf.wrapper'); 
        $pdf->loadHTML($this->convertGradesDataToHtmlCurrentTeacher());
        //loadHTML is the func that converts html data to pdf  
        return $pdf->stream();  //return!
        //stream func allows to show pdf file in browser 
    }

    function pdfOneSubject(){

        $this->subject = Subject::find(\request()->route('subject_id'));
        //dd($this->subject); // got subject id!! 

        $pdf = \App::make('dompdf.wrapper'); 
        $pdf->loadHTML($this->convertGradesDataToHtmlCurrentSubject());
        //loadHTML is the func that converts html data to pdf  
        return $pdf->stream();  //return!
        //stream func allows to show pdf file in browser 
    }

    function convertGradesDataToHtmlCurrentSubject(){
        //to use another font, we have to fix the encoding! 
        $output = '
        <head><style>body { font-family: DejaVu Sans }</style>
        </head> 
        <body>
        <h3 align="center">Група '.$this->group->number.'</h3>
        <table width="100%" style="border-collapse: collapse; border: 0px;">
        <tr>
            <th style="border: 1px solid; padding:12px;" width="30%">Студент</th>
            <th style="border: 1px solid; padding:12px;" width="30%">'.$this->subject->name.'</th>
        </tr>'; 
        foreach($this->group->students as $student ){ 
            $output .= '
            <tr>
                <td style="border: 1px solid; padding:12px;" width="30%">
                '.$student->user->name .'
                </td>'; 

                //open cell 
                $output .= '<td align="center" style="border: 1px solid; padding:12px;" width="30%">'; 
                foreach($student->grades as $grade){
                    if($grade->subject->id === $this->subject->id){
                    $output .= $grade->grade; 
                    
                    }
                }    
                $output .= '</td>' ;     //if there is a grade, fill it in, if there isn't - leave blank         

            $output .= '
            </tr>
            '; 
        }
        $output .= '</body>'; 
        return $output; 

    }

    //for ALL subjects:  
    function convertGradesDataToHtml(){
        $subjects = $this->subjects();
        //$subjects = Subject::all(); 
        // $this->group;

        //to use another font, we have to fix the encoding! 
        $output = '
        <head><style>body { font-family: DejaVu Sans }</style>
        </head> 
        <body>
        <h3 align="center">'.$this->group->number.'</h3>
        <table width="100%" style="border-collapse: collapse; border: 0px;">
        <tr>
            <th style="border: 1px solid; padding:12px;" width="30%">Student</th>'; 
        //all  columns for all subjects:
        foreach($subjects as $subject ){
            $output .= '<th style="border: 1px solid; padding:12px;" width="30%">'.$subject->name.'</th>'; 
        }   
        $output .= '</tr>';  //header of the table 

        foreach($this->group->students as $student ){ //contents
            $output .= '
            <tr>
                <td style="border: 1px solid; padding:12px;" width="30%">
                '.$student->user->name .'
                </td>'; 

                foreach($subjects as $subject ){
                    foreach($student->grades as $grade){
                        if($grade->subject->id === $subject->id){
                        $output .= '<td style="border: 1px solid; padding:12px;" width="30%">
                        '.$grade->grade .'
                        </td>' ;
                        }
                        
                    }                  
                } 

            $output .= '
            </tr>
            '; //also add a for-loop for all the subjects (but later, when i make sure this bit works) 
        }

        $output.='</table> </body>'; 

        return $output; 
    }

    // TODO: no two grades with the same student and subject ID!  
    // alternative: only grades for CURRENT subject! 
    function convertGradesDataToHtmlCurrentTeacher(){
        //$subjects = Auth::user()->teacher->subjects;
        $subjects = $this->subjects(); //gets only subjects of current teacher? 
        //$subjects = Subject::all(); 
        //dd($subjects); 
        //to use another font, we have to fix the encoding! 
        $output = '
        <head><style>body { font-family: DejaVu Sans }</style>
        </head> 
        <body>
        <h3 align="center">'.$this->group->number.'</h3>
        <table width="100%" style="border-collapse: collapse; border: 0px;">
        <tr>
            <th style="border: 1px solid; padding:12px;" width="30%">Student</th>'; 
        //all  columns for all subjects:
        foreach($subjects as $subject ){
            $output .= '<th style="border: 1px solid; padding:12px;" width="30%">'.$subject->name.'</th>'; 
        }   
        $output .= '</tr>';  //header of the table 

        foreach($this->group->students as $student ){ //contents
            $output .= '
            <tr>
                <td style="border: 1px solid; padding:12px;" width="30%">
                '.$student->user->name .'
                </td>'; 

                // foreach($subjects as $subject ){
                //     foreach($student->grades as $grade){
                //         if($grade->subject->id === $subject->id){
                //         $output .= '<td style="border: 1px solid; padding:12px;" width="30%">
                //         '.$grade->grade .'
                //         </td>' ;
                //         }
                        
                //     }                  
                // } 

                foreach($student->grades as $grade){
                    foreach($subjects as $subject){
                        if($grade->subject->id === $subject->id){
                        $output .= '<td style="border: 1px solid; padding:12px;" width="30%">
                        '.$grade->grade .'
                        </td>' ;
                        }
                        // else{
                        //     $output .= '<td style="border: 1px solid; padding:12px;" width="30%">
                        //     a
                        //     </td>' ; 
                        // }
                        
                    }                  
                }

            $output .= '
            </tr>
            '; //also add a for-loop for all the subjects (but later, when i make sure this bit works) 
        }

        $output.='</table> </body>'; 

        return $output; 
    }

    //for ONE subject: 
    


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
