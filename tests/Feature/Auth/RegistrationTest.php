<?php

namespace Tests\Feature\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;
use App\Models\Invitation;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $key = Invitation::create([
            'secret_key' => Str::random(32),
            'email' => 'newuser@test.ru',
        ]);

        $response = $this->get('/register/'.$key->secret_key);

        $response->assertStatus(200);
    }

    public function test_registration_screen_can_not_be_rendered()
    {
        $response = $this->get('/register/'.Str::random(32));

        $response->assertStatus(404);
    }

    public function test_new_users_can_register()
    {
        $key = Invitation::create([
            'secret_key' => Str::random(32),
            'email' => 'newuser@test.ru',
        ]);

        $response = $this->post('/register/'.$key->secret_key, [
            'name' => 'New User',
            'email' => 'newuser@test.ru',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
