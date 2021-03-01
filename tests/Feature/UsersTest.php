<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    // Test index function
    /** @test */
    public function admin_can_see_users_list()
    {
        $this->actingAsAdmin();
        $response = $this->get('/users')->assertOk();
    }

    /** @test */
    public function logged_in_users_can_not_see_users_list()
    {
        $this->actingAsUser();
        $response = $this->get('/users')->assertRedirect('/');
    }

    //Test update function
    /** @test */
    public function a_user_can_be_updated_by_admin(){
        $this->actingAsAdmin();

        $user = User::factory()->create();
        $user->save();

        $response = $this->patch('/users/'.$user->id, array_merge($this->data(), ['id' => $user->id]));

        $this->assertDatabaseHas('users', array_merge($this->data(), ['id' => $user->id]));
        $response->assertRedirect('/users');
    }

    /** @test */
    public function a_user_can_not_be_updated_by_user(){
        $this->actingAsUser();

        $user = User::factory()->create();
        $user->save();

        $response = $this->patch('/users/'.$user->id, array_merge($this->data(), ['id' => $user->id]));

        $this->assertDatabaseMissing('users', array_merge($this->data(), ['id' => $user->id]));
        $response->assertRedirect('/');
    }

    //Test destroy function
    /** @test */
    public function a_user_can_be_deleted_by_admin(){
        $this->actingAsAdmin();

        $user = User::factory()->create();
        $user->save();

        $response = $this->delete('/users/'.$user->id);

        $this->assertCount(1, User::all());
        $response->assertRedirect('/users');
    }

    /** @test */
    public function a_user_can_not_be_deleted_by_user(){
        $this->actingAsUser();

        $user = User::factory()->create();
        $user->save();

        $response = $this->delete('/users/'.$user->id);

        $this->assertCount(2, User::all());
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
            'role' => 'testing',
        ];
    }
}
