<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Member;
use App\Models\Permission;
use App\Models\Project;

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

    public function test_store()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('projects.store'), [
            'name' => 'Тестовый',
        ]);

        $response->assertStatus(404);
    }

    public function test_store_with_permission()
    {
        $user = User::factory()->create();
        $permission = Permission::create([
            'name' => 'Создание проектов',
            'slug' => 'create-projects'
        ]);
        $user->permissions()->attach($permission->id);

        $response = $this->actingAs($user)->post(route('projects.store'), [
            'name' => 'Тестовый',
        ]);

        $response->assertSessionHas('success');
        $response->assertRedirect(route('projects.index'));
    }

    public function test_show()
    {
        $user = User::factory()->create();
        $member = Member::create([
            'user_id' => $user->id
        ]);
        $project = Project::create([
            'name' => 'Тестовый'
        ]);
        $project->members()->attach($member->id);
        $response = $this->actingAs($user)->get(route('projects.show', ['project' => $project->id]));

        $response->assertStatus(200);
    }

    public function test_edit()
    {
        $user = User::factory()->create();
        $project = Project::create([
            'name' => 'Тестовый'
        ]);
        $response = $this->actingAs($user)->get(route('projects.edit', ['project' => $project->id]));

        $response->assertStatus(404);
    }

    public function test_edit_with_permission()
    {
        $user = User::factory()->create();
        $permission = Permission::create([
            'name' => 'Редактирование проектов',
            'slug' => 'edit-projects'
        ]);
        $user->permissions()->attach($permission->id);
        $member = Member::create([
            'user_id' => $user->id
        ]);
        $project = Project::create([
            'name' => 'Тестовый'
        ]);
        $project->members()->attach($member->id);
        $response = $this->actingAs($user)->get(route('projects.edit', ['project' => $project->id]));

        $response->assertStatus(200);
    }

    public function test_update()
    {
        $user = User::factory()->create();

        $project = Project::create([
            'name' => 'Тестовый'
        ]);

        $response = $this->actingAs($user)->put(route('projects.update', ['project' => $project->id]), [
            'name' => 'Тестовый правка',
        ]);

        $response->assertStatus(404);
    }

    public function test_update_with_permission()
    {
        $user = User::factory()->create();
        $permission = Permission::create([
            'name' => 'Редактирование проектов',
            'slug' => 'edit-projects'
        ]);

        $user->permissions()->attach($permission->id);

        $project = Project::create([
            'name' => 'Тестовый'
        ]);

        $response = $this->actingAs($user)->put(route('projects.update', ['project' => $project->id]), [
            'name' => 'Тестовый правка',
        ]);

        $response->assertSessionHas('success');
        $response->assertRedirect(route('projects.index'));
    }

    public function test_destroy()
    {
        $user = User::factory()->create();

        $project = Project::create([
            'name' => 'Тестовый'
        ]);

        $response = $this->actingAs($user)->delete(route('projects.destroy', ['project' => $project->id]));

        $response->assertStatus(404);
    }

    public function test_destroy_with_permission()
    {
        $user = User::factory()->create();
        $permission = Permission::create([
            'name' => 'Удаление проектов',
            'slug' => 'delete-projects'
        ]);

        $user->permissions()->attach($permission->id);

        $project = Project::create([
            'name' => 'Тестовый'
        ]);

        $response = $this->actingAs($user)->delete(route('projects.destroy', ['project' => $project->id]));

        $response->assertSessionHas('success');
        $response->assertRedirect(route('projects.index'));
    }
}
