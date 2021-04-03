<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Group;
use App\Models\Grade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StudentsTest extends TestCase
{
    use RefreshDatabase;

    // Test index function
    /** @test */
    public function logged_in_users_can_see_students_list()
    {
        $this->actingAsUser();
        $response = $this->get('/students')->assertOk();
    }

    /** @test */
    public function logged_out_users_can_not_see_students_list()
    {
        $response = $this->get('/students')->assertRedirect('/login');
    }
    
    // Test store function
    /** @test */
    public function a_student_can_be_stored_by_admin(){
        $this->actingAsAdmin();
        $data = $this->data();

        $response = $this->post('/students', $data);

        $this->assertDatabaseHas('students', $data);
        $response->assertRedirect('/students');
    }

    /** @test */
    public function a_student_can_not_be_stored_by_user(){
        $this->actingAsUser();

        $response = $this->post('/students', $this->data());

        $this->assertDatabaseMissing('students', $this->data());
        $response->assertRedirect('/');
    }

    //Test update function
    /** @test */
    public function a_student_can_be_updated_by_admin(){
        $this->actingAsAdmin();

        $student = Student::factory()->create();
        $student->save();

        $data = array_merge($this->data(), ['id' => $student->id]);

        $response = $this->patch('/students/'.$student->id, $data);

        $this->assertDatabaseHas('students', $data);
        $response->assertRedirect('/students');
    }

    /** @test */
    public function a_student_can_not_be_updated_by_user(){
        $this->actingAsUser();

        $student = Student::factory()->create();
        $student->save();

        $data = array_merge($this->data(), ['id' => $student->id]);
        
        $response = $this->patch('/students/'.$student->id, $data);

        $this->assertDatabaseMissing('students', $data);
        $response->assertRedirect('/');
    }

    //Test destroy function
    /** @test */
    public function a_student_can_be_deleted_by_admin(){
        $this->actingAsAdmin();

        $user = User::factory()->create(['role' => 'student']);
        $user->save();

        $student = Student::factory()->create(['user_id' => $user->id]);
        $student->save();

        $response = $this->delete('/students/'.$student->id);

        $this->assertCount(0, Student::all());
        $this->assertEquals($student->user->role, 'none');
        $response->assertRedirect('/students');
    }

    /** @test */
    public function a_student_can_not_be_deleted_by_user(){
        $this->actingAsUser();

        $user = User::factory()->create(['role' => 'student']);
        $user->save();

        $student = Student::factory()->create(['user_id' => $user->id]);
        $student->save();

        $response = $this->delete('/students/'.$student->id);

        $this->assertCount(1, Student::all());
        $this->assertEquals($student->user->role, 'student');
        $response->assertRedirect('/');
    }

    //TEST RATING GRADE
    /** @test */
    public function a_student_has_correct_rating_grade(){
        $this->actingAsUser();
        
        // 1. Студент
        $user = User::factory()->create(['role' => 'student']);
        $user->save();

        $student = Student::factory()->create(['user_id' => $user->id]);
        $student->save();

        // 2. Оцінка за перший предмет
        $first_subject = Subject::factory()->create(['credit' => 3]);
        $first_subject->save();

        $grade = Grade::factory()->create([
            'subject_id' => $first_subject->id, 
            'student_id' => $student->id,
            'grade' => 90
        ]);
        $grade->save();

        // 3. Оцінка за другий предмет
        $second_subject = Subject::factory()->create(['credit' => 5]);
        $second_subject->save();

        $grade = Grade::factory()->create([
            'subject_id' => $second_subject->id, 
            'student_id' => $student->id,
            'grade' => 70
        ]);
        $grade->save();

        $this->assertEquals($student->rating(), 77.5);
    }

    // Supporting functions
    private function actingAsAdmin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);
    }
    
    private function actingAsUser()
    {
        $user = User::factory()->create(); 
        $this->actingAs($user);
    }

    private function data()
    {
        return [
            'group_id' => "1",
            'user_id' => "1",
            'is_class_leader' => true, 
            'has_grant' => true, 
            'has_social_grant' => true,
        ];
    }
}
