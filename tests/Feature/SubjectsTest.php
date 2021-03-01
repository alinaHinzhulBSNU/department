<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubjectsTest extends TestCase
{
    use RefreshDatabase;

    // Test index function
    /** @test */
    public function admin_can_see_subjects_list()
    {
        $this->actingAsAdmin();
        $response = $this->get('/subjects')->assertOk();
    }

    /** @test */
    public function logged_in_users_can_not_see_subjects_list()
    {
        $this->actingAsUser();
        $response = $this->get('/subjects')->assertRedirect('/');
    }

    /** @test */
    public function logged_out_users_can_not_see_subjects_list()
    {
        $response = $this->get('/subjects')->assertRedirect('/login');
    }
    
    // Test store function
    /** @test */
    public function a_subject_can_be_stored_by_admin(){
        $this->actingAsAdmin();

        $response = $this->post('/subjects', $this->data());

        $this->assertDatabaseHas('subjects', $this->data());
        $response->assertRedirect('/subjects');
    }

    /** @test */
    public function a_subject_can_not_be_stored_by_user(){
        $this->actingAsUser();

        $response = $this->post('/subjects', $this->data());

        $this->assertDatabaseMissing('subjects', $this->data());
        $response->assertRedirect('/');
    }

    //Test update function
    /** @test */
    public function a_subject_can_be_updated_by_admin(){
        $this->actingAsAdmin();

        $subject = Subject::factory()->create();
        $subject->save();

        $data = array_merge($this->data(), ['id' => $subject->id]);

        $response = $this->patch('/subjects/'.$subject->id, $data);

        $this->assertDatabaseHas('subjects', $data);
        $response->assertRedirect('/subjects');
    }

    /** @test */
    public function a_subject_can_not_be_updated_by_user(){
        $this->actingAsUser();

        $subject = Subject::factory()->create();
        $subject->save();

        $data = array_merge($this->data(), ['id' => $subject->id]);

        $response = $this->patch('/subjects/'.$subject->id, $data);

        $this->assertDatabaseMissing('subjects', $data);
        $response->assertRedirect('/');
    }

    //Test destroy function
    /** @test */
    public function a_subject_can_be_deleted_by_admin(){
        $this->actingAsAdmin();

        $subject = Subject::factory()->create();
        $subject->save();

        $response = $this->delete('/subjects/'.$subject->id);

        $this->assertCount(0, Subject::all());
        $response->assertRedirect('/subjects');
    }

    /** @test */
    public function a_subject_can_not_be_deleted_by_user(){
        $this->actingAsUser();

        $subject = Subject::factory()->create();
        $subject->save();

        $response = $this->delete('/subjects/'.$subject->id);

        $this->assertCount(1, Subject::all());
        $response->assertRedirect('/');
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
            'name' => 'testing',
            'exam_type' => 'testing',
            'description' => 'testing',
            'credit' => 3.5,
            'teacher_id' => 1,
        ];
    }
}
