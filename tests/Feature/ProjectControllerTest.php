<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class ProjectControllerTest extends TestCase
{
    public function test_index()
    {
        $response = $this->get(route('projects.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_auth_index()
    {
        $user = User::factory()->make();
        $response = $this->actingAs($user)->get(route('projects.index'));

        $response->assertStatus(200);
    }
}
