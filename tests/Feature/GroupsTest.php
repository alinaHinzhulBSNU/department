<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupsTest extends TestCase
{
    use RefreshDatabase;

    // Test index function
    /** @test */
    public function logged_in_users_can_see_groups_list()
    {
        $this->actingAsUser();
        $response = $this->get('/groups')->assertOk();
    }

    /** @test */
    public function logged_out_users_can_not_see_groups_list()
    {
        $response = $this->get('/groups')->assertRedirect('/login');
    }
    
    // Test store function
    /** @test */
    public function a_group_can_be_stored_by_admin(){
        $this->actingAsAdmin();
        $response = $this->post('/groups', $this->data());

        $this->assertDatabaseHas('groups', $this->data());
        $response->assertRedirect('/groups');
    }

    /** @test */
    public function a_group_can_not_be_stored_by_user(){
        $this->actingAsUser();
        $response = $this->post('/groups', $this->data());

        $this->assertDatabaseMissing('groups', $this->data());
        $response->assertRedirect('/groups');
    }

    //Test update function
    /** @test */
    public function a_group_can_be_updated_by_admin(){
        $this->actingAsAdmin();

        $group = Group::factory()->create();
        $group->save();

        $data = array_merge($this->data(), ['id' => $group->id]);

        $response = $this->patch('/groups/'.$group->id, $data);

        $this->assertDatabaseHas('groups', $data);
        $response->assertRedirect('/groups');
    }

    /** @test */
    public function a_group_can_not_be_updated_by_user(){
        $this->actingAsUser();

        $group = Group::factory()->create();
        $group->save();

        $data = array_merge($this->data(), ['id' => $group->id]);

        $response = $this->patch('/groups/'.$group->id, $data);

        $this->assertDatabaseMissing('groups', $data);
        $response->assertRedirect('/groups');
    }

    //Test destroy function
    /** @test */
    public function a_group_can_be_deleted_by_admin(){
        $this->actingAsAdmin();

        $group = Group::factory()->create();
        $group->save();

        $response = $this->delete('/groups/'.$group->id);

        $this->assertCount(0, Group::all());
        $response->assertRedirect('/groups');
    }

    /** @test */
    public function a_group_can_not_be_deleted_by_user(){
        $this->actingAsUser();

        $group = Group::factory()->create();
        $group->save();

        $response = $this->delete('/groups/'.$group->id);

        $this->assertCount(1, Group::all());
        $response->assertRedirect('/groups');
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
            "number" => "308",
            "course" => 1,
            'major' => "testing",
            'start_year' => 2018,
            'end_year' => 2022,
        ];
    }
}
