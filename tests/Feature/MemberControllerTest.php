<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Member;
use App\Models\Permission;
use App\Models\Project;

class MemberControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('members.index'));

        $response->assertStatus(404);
    }

    public function test_index_with_permission()
    {
        $user = User::factory()->create();
        $permission = Permission::create([
            'name' => 'Просмотр участников',
            'slug' => 'view-members'
        ]);
        $user->permissions()->attach($permission->id);

        $response = $this->actingAs($user)->get(route('members.index'));

        $response->assertStatus(200);
    }

    public function test_create()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('members.create'));

        $response->assertStatus(404);
    }

    public function test_create_with_permission()
    {
        $user = User::factory()->create();
        $permission = Permission::create([
            'name' => 'Создание участников',
            'slug' => 'create-members'
        ]);
        $user->permissions()->attach($permission->id);

        $response = $this->actingAs($user)->get(route('members.create'));

        $response->assertStatus(200);
    }

    public function test_store()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('members.store'), [
            'user_id' => $user->id,
        ]);

        $response->assertStatus(404);
    }

    public function test_store_with_permission()
    {
        $user = User::factory()->create();
        $permission = Permission::create([
            'name' => 'Создание участников',
            'slug' => 'create-members'
        ]);
        $user->permissions()->attach($permission->id);
        $project = Project::create([
            'name' => 'Тестовый'
        ]);

        $response = $this->actingAs($user)->post(route('members.store'), [
            'user_id' => $user->id,
            'project_ids' => [$project->id]
        ]);

        $response->assertSessionHas('success');
        $response->assertRedirect(route('members.index'));
    }

    public function test_edit()
    {
        $user = User::factory()->create();
        $member = Member::create([
            'user_id' => $user->id
        ]);
        $response = $this->actingAs($user)->get(route('members.edit', ['member' => $member->id]));

        $response->assertStatus(404);
    }

    public function test_edit_with_permission()
    {
        $user = User::factory()->create();
        $permission = Permission::create([
            'name' => 'Редактирование участников',
            'slug' => 'edit-members'
        ]);
        $user->permissions()->attach($permission->id);
        $member = Member::create([
            'user_id' => $user->id
        ]);
        $response = $this->actingAs($user)->get(route('members.edit', ['member' => $member->id]));

        $response->assertStatus(200);
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $member = Member::create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->put(route('members.update', ['member' => $member->id]), [
            'user_id' => 12345,
        ]);

        $response->assertStatus(404);
    }

    public function test_update_with_permission()
    {
        $user = User::factory()->create();
        $permission = Permission::create([
            'name' => 'Редактирование участников',
            'slug' => 'edit-members'
        ]);
        $user->permissions()->attach($permission->id);
        $member = Member::create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->put(route('members.update', ['member' => $member->id]), [
            'user_id' => 12345,
        ]);

        $response->assertSessionHas('success');
        $response->assertRedirect(route('members.index'));
    }

    public function test_destroy()
    {
        $user = User::factory()->create();
        $member = Member::create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->delete(route('members.destroy', ['member' => $member->id]));

        $response->assertStatus(404);
    }

    public function test_destroy_with_permission()
    {
        $user = User::factory()->create();
        $permission = Permission::create([
            'name' => 'Удаление участников',
            'slug' => 'delete-members'
        ]);
        $user->permissions()->attach($permission->id);
        $member = Member::create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->delete(route('members.destroy', ['member' => $member->id]));

        $response->assertSessionHas('success');
        $response->assertRedirect(route('members.index'));
    }
}
