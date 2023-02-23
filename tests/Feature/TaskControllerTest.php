<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Permission;
use App\Models\Member;
use App\Models\Project;
use App\Models\Stage;
use App\Models\Task;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $response = $this->get(route('tasks.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_auth_index()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('tasks.index'));

        $response->assertStatus(200);
    }

    public function test_create()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('tasks.create'));

        $response->assertStatus(404);
    }

    public function test_create_with_permission()
    {
        $user = User::factory()->create();
        $permission = Permission::create([
            'name' => 'Создание задач',
            'slug' => 'create-tasks'
        ]);
        $user->permissions()->attach($permission->id);

        $response = $this->actingAs($user)->get(route('tasks.create'));

        $response->assertStatus(200);
    }

    public function test_store()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('tasks.store'), [
            'name' => 'Тестовая задача',
        ]);

        $response->assertStatus(404);
    }

    public function test_store_with_permission()
    {
        $entities = $this->getEntitiesForTask('create-tasks');

        $response = $this->actingAs($entities['user'])->post(route('tasks.store'), [
            'name' => 'Тестовая задача',
            'owner_id' => $entities['member']->id,
            'responsible_id' => $entities['member']->id,
            'project_id' => $entities['project']->id,
            'stage_id' => $entities['stage']->id,
        ]);

        $response->assertSessionHas('success');
        $response->assertRedirect(route('tasks.index'));
    }

    public function test_show()
    {
        $entities = $this->getEntitiesForTask();
        $task = Task::create([
            'name' => 'Тестовая задача',
            'owner_id' => $entities['member']->id,
            'responsible_id' => $entities['member']->id,
            'project_id' => $entities['project']->id,
            'stage_id' => $entities['stage']->id,
        ]);
        $response = $this->actingAs($entities['user'])->get(route('tasks.show', ['task' => $task->id]));

        $response->assertStatus(200);
    }

    public function test_show_not_member()
    {
        $entities = $this->getEntitiesForTask();
        $otherUser = User::factory()->create();
        $otherMember = Member::create([
            'user_id' => $otherUser->id
        ]);
        $task = Task::create([
            'name' => 'Тестовая задача',
            'owner_id' => $otherMember->id,
            'responsible_id' => $otherMember->id,
            'project_id' => $entities['project']->id,
            'stage_id' => $entities['stage']->id,
        ]);
        $response = $this->actingAs($entities['user'])->get(route('tasks.show', ['task' => $task->id]));

        $response->assertStatus(404);
    }

    public function test_edit()
    {
        $entities = $this->getEntitiesForTask();
        $task = Task::create([
            'name' => 'Тестовая задача',
            'owner_id' => $entities['member']->id,
            'responsible_id' => $entities['member']->id,
            'project_id' => $entities['project']->id,
            'stage_id' => $entities['stage']->id,
        ]);
        $response = $this->actingAs($entities['user'])->get(route('tasks.edit', ['task' => $task->id]));

        $response->assertStatus(404);
    }

    public function test_edit_with_permission()
    {
        $entities = $this->getEntitiesForTask('edit-tasks');
        $task = Task::create([
            'name' => 'Тестовая задача',
            'owner_id' => $entities['member']->id,
            'responsible_id' => $entities['member']->id,
            'project_id' => $entities['project']->id,
            'stage_id' => $entities['stage']->id,
        ]);
        $response = $this->actingAs($entities['user'])->get(route('tasks.edit', ['task' => $task->id]));

        $response->assertStatus(200);
    }

    public function test_update()
    {
        $entities = $this->getEntitiesForTask();
        $task = Task::create([
            'name' => 'Тестовая задача',
            'owner_id' => $entities['member']->id,
            'responsible_id' => $entities['member']->id,
            'project_id' => $entities['project']->id,
            'stage_id' => $entities['stage']->id,
        ]);

        $response = $this->actingAs($entities['user'])->put(route('tasks.update', ['task' => $task->id]), [
            'name' => 'Тестовая задача - правка',
        ]);

        $response->assertStatus(404);
    }

    public function test_update_with_permission()
    {
        $entities = $this->getEntitiesForTask('edit-tasks');
        $task = Task::create([
            'name' => 'Тестовая задача',
            'owner_id' => $entities['member']->id,
            'responsible_id' => $entities['member']->id,
            'project_id' => $entities['project']->id,
            'stage_id' => $entities['stage']->id,
        ]);

        $response = $this->actingAs($entities['user'])->put(route('tasks.update', ['task' => $task->id]), [
            'name' => 'Тестовая задача - правка',
            'owner_id' => $entities['member']->id,
            'responsible_id' => $entities['member']->id,
            'project_id' => $entities['project']->id,
            'stage_id' => $entities['stage']->id,
        ]);

        $response->assertSessionHas('success');
        $response->assertRedirect(route('tasks.index'));
    }

    public function test_update_stage()
    {
        $entities = $this->getEntitiesForTask();
        $task = Task::create([
            'name' => 'Тестовая задача',
            'owner_id' => $entities['member']->id,
            'responsible_id' => $entities['member']->id,
            'project_id' => $entities['project']->id,
            'stage_id' => $entities['stage']->id,
        ]);

        $newStage = Stage::create([
            'name' => 'В процессе',
            'slug' => 'in_process'
        ]);

        $response = $this->actingAs($entities['user'])->patchJson(
            route('tasks.update-stage', ['task' => $task->id]),
            ['stage_id' => $newStage->id]
        );

        $response->assertJson([
            'status' => 'success',
            'text' => __('success_messages.stage_changed')
        ]);
    }

    public function test_destroy()
    {
        $entities = $this->getEntitiesForTask();
        $task = Task::create([
            'name' => 'Тестовая задача',
            'owner_id' => $entities['member']->id,
            'responsible_id' => $entities['member']->id,
            'project_id' => $entities['project']->id,
            'stage_id' => $entities['stage']->id,
        ]);

        $response = $this->actingAs($entities['user'])->delete(route('tasks.destroy', ['task' => $task->id]));

        $response->assertStatus(404);
    }

    public function test_destroy_with_permission()
    {
        $entities = $this->getEntitiesForTask('delete-tasks');
        $task = Task::create([
            'name' => 'Тестовая задача',
            'owner_id' => $entities['member']->id,
            'responsible_id' => $entities['member']->id,
            'project_id' => $entities['project']->id,
            'stage_id' => $entities['stage']->id,
        ]);

        $response = $this->actingAs($entities['user'])->delete(route('tasks.destroy', ['task' => $task->id]));

        $response->assertSessionHas('success');
        $response->assertRedirect(route('tasks.index'));
    }

    private function getEntitiesForTask($permission = null): array
    {
        $user = User::factory()->create();
        if ($permission)
        {
            $perm = Permission::create([
                'name' => $permission,
                'slug' => $permission
            ]);
            $user->permissions()->attach($perm->id);
        }

        $member = Member::create([
            'user_id' => $user->id
        ]);
        $project = Project::create([
            'name' => 'Тестовый'
        ]);
        $project->members()->attach($member->id);
        $stage = Stage::create([
            'name' => 'Новая',
            'slug' => 'new'
        ]);

        return [
            'user' => $user,
            'member' => $member,
            'project' => $project,
            'stage' => $stage,
        ];
    }
}
