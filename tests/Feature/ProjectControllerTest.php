<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Permission;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $response = $this->get(route('projects.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_auth_index()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('projects.index'));

        $response->assertStatus(200);
    }

    public function test_create()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('projects.create'));

        $response->assertStatus(404);
    }

    public function test_create_with_permission()
    {
        $user = User::factory()->create();
        $permission = Permission::create([
            'name' => 'Создание проектов',
            'slug' => 'create-projects'
        ]);
        $user->permissions()->attach($permission->id);

        $response = $this->actingAs($user)->get(route('projects.create'));

        $response->assertStatus(200);
    }
}
