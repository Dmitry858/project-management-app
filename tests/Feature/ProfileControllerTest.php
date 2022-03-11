<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group controllers
     * @group profile
     */
    public function test_index()
    {
        $response = $this->get(route('profile'));

        $response->assertRedirect(route('login'));
    }

    /**
     * @group controllers
     * @group profile
     */
    public function test_auth_index()
    {
        $user = User::factory()->make();
        $response = $this->actingAs($user)->get(route('profile'));

        $response->assertStatus(200);
    }

    /**
     * @group controllers
     * @group profile
     */
    public function test_update_profile()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'password_confirmation' => $user->password,
        ]);

        $response->assertSessionHas('success');
        $response->assertRedirect(route('profile'));
    }

    /**
     * @group controllers
     * @group profile
     */
    public function test_update_profile_with_empty_email()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post(route('profile.update'), [
            'name' => $user->name,
            'email' => '',
            'password' => $user->password,
            'password_confirmation' => $user->password,
        ])->assertSessionHasErrors(['email']);
    }
}
