<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GradesTest extends TestCase
{
    use RefreshDatabase;

    private $group;
    private $student;

    public function setUp(): void {
        parent::setUp();

        //anything you need to do before every test goes here 
        $this->group = Group::factory()->create();
        $this->group->save();

        $this->student = Student::factory()->create();
        $this->student->save();
    }

    // TEST INDEX FUNCTION
    /** @test */
    public function logged_in_users_can_see_grades_of_a_group(){
        $this->actingAsUser();
        $response = $this->get('/grades/'.$this->group->id)->assertOk();
    }
    /** @test */
    public function logged_out_user_cannot_see_grades_of_a_group(){  
        $response = $this->get('/grades/'.$this->group->id)->assertRedirect('/login'); 
    }

    // TEST STORE FUNCTION
    /** @test */
    public function a_grade_can_be_added_through_form_by_teacher(){ 
        $this->actingAsTeacher(); 
        $response = $this->post('/grades/'.$this->group->id, $this->data());
        $this->assertDatabaseHas('grades', $this->data());
    }
    /** @test */
    public function a_grade_cannot_be_added_by_user(){
        $this->actingAsUser();
        $response = $this->post('/grades/'.$this->group->id, $this->data());
        $this->assertDatabaseMissing('grades', $this->data());
    }
    /** @test */
    public function a_grade_cannot_be_added_by_admin(){
        $this->actingAsAdmin();
        $response = $this->post('/grades/'.$this->group->id, $this->data());
        $this->assertDatabaseMissing('grades', $this->data());
    }
    /** @test */
    public function a_grade_can_be_added_twice_by_teacher(){ 
        $this->actingAsTeacher(); 

        // 1. Додати оцінку перший раз
        $response = $this->post('/grades/'.$this->group->id, $this->data());
        $this->assertDatabaseHas('grades', $this->data());

        // 2. Спроба додати оцінку другий раз
        $response = $this->post('/grades/'.$this->group->id, $this->data());
        $this->assertCount(1, Grade::all());
    }

    // TEST UPDATE FUNCTION
    /** @test */
    public function a_grade_can_be_updated_by_the_teacher_of_the_given_subject(){
        // 1. Авторизація
        $user = User::factory()->create(['role' => 'teacher']);
        $this->actingAs($user);

        // 2. Реєстрація викладача в БД
        $teacher = Teacher::factory()->create(['user_id' => $user->id]);
        $teacher->save();

        // 3. Дисципліна, яку веде АВТОРИЗОВАНИЙ викладач
        $subject = Subject::factory()->create(['teacher_id' => $teacher->id]);
        $subject->save();

        // 4. Оцінка саме з тієї дисципліни, яку веде даний викладач
        $grade = Grade::factory()->create(['subject_id' => $subject->id, 'student_id' => $this->student->id]);
        $grade->save();

        $data = [
            'id' => $grade->id,
            'subject_id' => $grade->subject_id, 
            'student_id' => $grade->student_id,
            'semester' => $grade->semester,
            'grade' => 95,
        ];

        $response = $this->patch('/grades/'.$this->group->id.'/'.$grade->id, $data);

        $this->assertDatabaseHas('grades', $data);
        $response->assertRedirect('/grades/'.$this->group->id);
    }
    /** @test */
    public function a_grade_can_not_be_updated_by_the_teacher_of_another_subject(){
        // 1. Авторизація
        $current_user = User::factory()->create(['role' => 'none']);
        $this->actingAs($current_user);

        $another_user = User::factory()->create(['role' => 'teacher']);
        $another_user->save();

        // 2. Реєстрація викладачів в БД
        $current_teacher = Teacher::factory()->create(['user_id' => $current_user->id]);
        $current_teacher->save();

        $another_teacher = Teacher::factory()->create(['user_id' => $another_user->id]);
        $another_teacher->save();

        // 3. Дисципліна, яку веде ІНШИЙ викладач
        $subject = Subject::factory()->create(['teacher_id' => $another_teacher->id]);
        $subject->save();

        // 4. Оцінка з ІНШОЇ дисципліни, яку веде ІНШИЙ викладач
        $grade = Grade::factory()->create(['subject_id' => $subject->id, 'student_id' => $this->student->id]);
        $grade->save();

        $data = [
            'id' => $grade->id,
            'subject_id' => $grade->subject_id, 
            'student_id' => $grade->student_id,
            'semester' => $grade->semester,
            'grade' => 95,
        ];

        $response = $this->patch('/grades/'.$this->group->id.'/'.$grade->id, $data);
        
        $this->assertDatabaseMissing('grades', $data);
        $response->assertRedirect('/grades/'.$this->group->id);
    }

    // TEST DESTROY FUNCTION
    /** @test */
    public function a_grade_can_be_deleted_by_the_teacher_of_the_given_subject(){
        // 1. Авторизація
        $user = User::factory()->create(['role' => 'teacher']);
        $this->actingAs($user);

        // 2. Реєстрація викладача в БД
        $teacher = Teacher::factory()->create(['user_id' => $user->id]);
        $teacher->save();

        // 3. Дисципліна, яку веде АВТОРИЗОВАНИЙ викладач
        $subject = Subject::factory()->create(['teacher_id' => $teacher->id]);
        $subject->save();

        // 4. Оцінка саме з тієї дисципліни, яку веде даний викладач
        $grade = Grade::factory()->create(['subject_id' => $subject->id, 'student_id' => $this->student->id]);
        $grade->save();

        //fwrite(STDERR, print_r(Auth::user()->role, TRUE));

        $response = $this->delete('/grades/'.$this->group->id.'/'.$grade->id);

        $this->assertCount(0, Grade::all());
        $response->assertRedirect('/grades/'.$this->group->id);
    }
    /** @test */
    public function a_grade_can_not_be_deleted_by_the_teacher_of_another_subject(){
        // 1. Авторизація
        $current_user = User::factory()->create(['role' => 'teacher']);
        $this->actingAs($current_user);

        $another_user = User::factory()->create(['role' => 'teacher']);
        $another_user->save();

        // 2. Реєстрація викладачів в БД
        $current_teacher = Teacher::factory()->create(['user_id' => $current_user->id]);
        $current_teacher->save();

        $another_teacher = Teacher::factory()->create(['user_id' => $another_user->id]);
        $another_teacher->save();

        // 3. Дисципліна, яку веде ІНШИЙ викладач
        $subject = Subject::factory()->create(['teacher_id' => $another_teacher->id]);
        $subject->save();

        // 4. Оцінка з ІНШОЇ дисципліни, яку веде ІНШИЙ викладач
        $grade = Grade::factory()->create(['subject_id' => $subject->id, 'student_id' => $this->student->id]);
        $grade->save();

        $response = $this->delete('/grades/'.$this->group->id.'/'.$grade->id);

        $this->assertCount(1, Grade::all());
        $response->assertRedirect('/grades/'.$this->group->id);
    }

    //INPUT VALIDATION: 
    /** @test */
    public function grade_cannot_exceed_100(){   
        $this->actingAsTeacher(); 
        $response = $this->post('/grades/1', 
            array_merge($this->data(), ['grade' => 120])
        );  
        $response->assertSessionHasErrors(['grade']); 
        $this->assertCount(0, Grade::all());  
    }
    /** @test */
    public function student_id_is_required_to_add_grade(){ 
        $this->actingAsTeacher(); 
        $response = $this->post('/grades/1', 
            array_merge($this->data(), ['student_id' => null ])
        );  
        $response->assertSessionHasErrors(['student_id']); 
        $this->assertCount(0, Grade::all()); 
    }
    /** @test */
    public function subject_id_is_required_to_add_grade(){ 
        $this->actingAsTeacher(); 
        $response = $this->post('/grades/1', 
            array_merge($this->data(), ['subject_id' => null ])
        );  
        $response->assertSessionHasErrors(['subject_id']); 
        $this->assertCount(0, Grade::all()); 
    }
    /** @test */
    public function semester_number_cannot_exceed_12(){   
        $this->actingAsTeacher(); 
        $response = $this->post('/grades/1', 
            array_merge($this->data(), ['semester' => 13])
        );  
        $response->assertSessionHasErrors(['semester']); 
        $this->assertCount(0, Grade::all());  
    }
    /** @test */
    public function semester_number_cannot_be_less_than_1(){  
        $this->actingAsTeacher(); 
        $response = $this->post('/grades/1', 
            array_merge($this->data(), ['semester' => 0])
        );  
        $response->assertSessionHasErrors(['semester']); 
        $this->assertCount(0, Grade::all());   

    }

    // ТЕСТУВАННЯ ПЕРЕВЕДЕННЯ ОЦІІНКИ В БАДАХ В ОЦІНКУ ЗА ІНШОЮ ШКАЛОЮ 
    // (ПАРАМЕТРИЗОВАНІ ТЕСТИ)

    /** @dataProvider providerNationalGradesData */
    public function testConverToNational($grade, $national) {
        $grade = Grade::factory()->create(['grade' => $grade]);
        $this->assertEquals($grade->toNational(), $national);
    }

    // Аналіз граничних значень
    public function providerNationalGradesData() {
        // test with this values
        return array(
            array(0, "Незадовільно (борг)"),
            array(1, "Незадовільно (борг)"),
            array(58, "Незадовільно (борг)"),
            array(59, "Незадовільно (борг)"),
            array(60, "Задовільно"),
            array(61, "Задовільно"),
            array(73, "Задовільно"),
            array(74, "Задовільно"),
            array(75, "Добре"),
            array(76, "Добре"),
            array(88, "Добре"),
            array(89, "Добре"),
            array(90, "Відмінно"),
            array(91, "Відмінно"),
            array(99, "Відмінно"),
            array(100, "Відмінно"),
        );
    }

    /** @dataProvider providerECTSGradesData */
    public function testConverToECTS($grade, $ECTS) {
        $grade = Grade::factory()->create(['grade' => $grade]);
        $this->assertEquals($grade->toECTS(), $ECTS);
    }

    // Розбиття на класи еквівалентності
    public function providerECTSGradesData() {
        return array(
            array(20, "F"),
            array(40, "FX"),
            array(62, "E"),
            array(70, "D"),
            array(78, "C"),
            array(85, "B"),
            array(95, "A"),
        );
    }

    // Helper functions: 
    private function actingAsAdmin(){
        $admin = User::factory()->create(['role' => 'admin']); //we can override any of the fields inside create()
        $this->actingAs($admin);
    }
      
    private function actingAsUser(){
        $user = User::factory()->create(); //role = none 
        $this->actingAs($user);
    }

    private function actingAsTeacher(){
        $teacher = User::factory()->create(['role' => 'teacher']);
        $this->actingAs($teacher);
    }
  
    //valid data: 
    private function data(){ 
        return [
            'student_id' => 1,
            'subject_id' => 1,
            'semester' => 1,
            'grade' => 95,
        ]; 
    }
}