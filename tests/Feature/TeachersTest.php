<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeachersTest extends TestCase
{
    use RefreshDatabase;

    // Test index function
    /** @test */
    public function admin_can_see_teachers_list()
    {
        $this->actingAsAdmin();
        $response = $this->get('/teachers')->assertOk();
    }

    /** @test */
    public function logged_in_users_can_not_see_teachers_list()
    {
        $this->actingAsUser();
        $response = $this->get('/teachers')->assertRedirect('/');
    }

    /** @test */
    public function logged_out_users_can_not_see_teachers_list()
    {
        $response = $this->get('/teachers')->assertRedirect('/login');
    }
    
    // Test store function
    /** @test */
    public function a_teacher_can_be_stored_by_admin(){
        $this->actingAsAdmin();

        $response = $this->post('/teachers', $this->data());

        $this->assertDatabaseHas('teachers', $this->data());
        $response->assertRedirect('/teachers');
    }

    /** @test */
    public function a_teacher_can_not_be_stored_by_user(){
        $this->actingAsUser();

        $response = $this->post('/teachers', $this->data());

        $this->assertDatabaseMissing('teachers', $this->data());
        $response->assertRedirect('/');
    }

    //Test update function
    /** @test */
    public function a_teacher_can_be_updated_by_admin(){
        $this->actingAsAdmin();

        $teacher = Teacher::factory()->create();
        $teacher->save();

        $data = array_merge($this->data(), ['id' => $teacher->id]);

        $response = $this->patch('/teachers/'.$teacher->id, $data);

        $this->assertDatabaseHas('teachers', $data);
        $response->assertRedirect('/teachers');
    }

    /** @test */
    public function a_teacher_can_not_be_updated_by_user(){
        $this->actingAsUser();

        $teacher = Teacher::factory()->create();
        $teacher->save();

        $data = array_merge($this->data(), ['id' => $teacher->id]);

        $response = $this->patch('/teachers/'.$teacher->id, $data);

        $this->assertDatabaseMissing('teachers', $data);
        $response->assertRedirect('/');
    }

    //Test destroy function
    /** @test */
    public function a_teacher_can_be_deleted_by_admin(){
        $this->actingAsAdmin();

        $user = User::factory()->create(['role' => 'teacher']);
        $user->save();

        $teacher = Teacher::factory()->create(['user_id' => $user->id]);
        $teacher->save();

        $response = $this->delete('/teachers/'.$teacher->id);

        $this->assertCount(0, Teacher::all());
        $this->assertEquals($teacher->user->role, 'none');
        $response->assertRedirect('/teachers');
    }

    /** @test */
    public function a_group_can_not_be_deleted_by_user(){
        $this->actingAsUser();

        $user = User::factory()->create(['role' => 'teacher']);
        $user->save();

        $teacher = Teacher::factory()->create(['user_id' => $user->id]);
        $teacher->save();

        $response = $this->delete('/teachers/'.$teacher->id);

        $this->assertCount(1, Teacher::all());
        $this->assertEquals($teacher->user->role, 'teacher');
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
            'degree' => 'testing',
            'user_id' => 1,
            'department' => 'testing',
        ];
    }
}
