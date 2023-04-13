<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Permission;

class SettingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $response = $this->get(route('settings'));

        $response->assertRedirect(route('login'));
    }

    public function test_auth_index()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('settings'));

        $response->assertStatus(404);
    }

    public function test_auth_index_with_permission()
    {
        $user = $this->getUserWithPermission();
        $response = $this->actingAs($user)->get(route('settings'));

        $response->assertStatus(200);
    }

    public function test_index_general()
    {
        $response = $this->get(route('settings.general'));

        $response->assertRedirect(route('login'));
    }

    public function test_auth_index_general()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('settings.general'));

        $response->assertStatus(404);
    }

    public function test_auth_index_general_with_permission()
    {
        $user = $this->getUserWithPermission();
        $response = $this->actingAs($user)->get(route('settings.general'));

        $response->assertStatus(200);
    }

    public function test_update_general()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('settings.general.update'));

        $response->assertStatus(404);
    }

    public function test_update_general_with_permission()
    {
        $user = $this->getUserWithPermission();
        $response = $this->actingAs($user)->post(route('settings.general.update'));

        $response->assertSessionHas('success');
        $response->assertRedirect(route('settings.general'));
    }

    public function test_index_caching()
    {
        $response = $this->get(route('settings.caching'));

        $response->assertRedirect(route('login'));
    }

    public function test_auth_index_caching()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('settings.caching'));

        $response->assertStatus(404);
    }

    public function test_auth_index_caching_with_permission()
    {
        $user = $this->getUserWithPermission();
        $response = $this->actingAs($user)->get(route('settings.caching'));

        $response->assertStatus(200);
    }

    public function test_update_caching()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('settings.caching.update'));

        $response->assertStatus(404);
    }

    public function test_update_caching_with_permission()
    {
        $user = $this->getUserWithPermission();
        $response = $this->actingAs($user)->post(
            route('settings.caching.update'),
            ['clear_cache' => 1]
        );

        $response->assertSessionHas('success');
        $response->assertRedirect(route('settings.caching'));
    }

    private function getUserWithPermission()
    {
        $user = User::factory()->create();
        $permission = Permission::create([
            'name' => 'Редактирование настроек',
            'slug' => 'edit-settings'
        ]);
        $user->permissions()->attach($permission->id);

        return $user;
    }
}
