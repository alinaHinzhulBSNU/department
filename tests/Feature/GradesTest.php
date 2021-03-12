<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Grade;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GradesTest extends TestCase{

    use RefreshDatabase; 

   public function setUp(): void {
        parent::setUp(); 
        //anything you need to do before every test goes here 
   }

   // SEE 
   /** @test */
   public function logged_out_user_cannot_see_grades_of_a_group(){  
        $response = $this->get('/grades/1')->assertRedirect('/login'); 
   }

    /** @test */
    public function a_grade_can_be_added_through_form_by_teacher(){ 
        $this->actingAsTeacher(); 
        
        $response = $this->post('/grades/1', [
            'subject_id' => '1', 
            'student_id' => '1', 
            'grade' => 95,  
            'semester' => 2, 
        ]); 
        
        $this->assertCount(1, Grade::all()); //total of one grade after the procedure 
    }
  
     /** @test */
     public function a_grade_cannot_be_added_by_user(){
        $this->actingAsUser();
        $response = $this->post('/grades/1', $this->data());
        $this->assertDatabaseMissing('grades', $this->data());
    }

      /** @test */
      public function a_grade_cannot_be_added_by_admin(){
        $this->actingAsAdmin();
        $response = $this->post('/grades/1', $this->data());
        $this->assertDatabaseMissing('grades', $this->data());
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


      // Helper functions: 
      private function actingAsAdmin()
      {
          $admin = User::factory()->create(['role' => 'admin']); //we can override any of the fields inside create()
          $this->actingAs($admin);
      }
      
      private function actingAsUser()
      {
          $user = User::factory()->create(); //role = none 
          $this->actingAs($user);
      }

      private function actingAsTeacher(){
            $teacher = User::factory()->create(['role' => 'teacher']);
            $this->actingAs($teacher);
      }
  
      //valid data: 
      private function data()
      { 
          return [
            'subject_id' => 1, //['required'],
            'student_id' => 1, //['required'],
            'grade' => 95, //['required', 'integer', 'min:0', 'max:100'],
            'semester' => 2, //['required', 'integer', 'min:1', 'max:12'],
          ]; 
      }
}