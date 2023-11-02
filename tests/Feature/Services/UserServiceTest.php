<?php

namespace Tests\Feature\Services;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Http\Requests\UpdateUserRequest;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group services
     */
    public function test_get()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->actingAs($user1);
        $foundUser = $this->getUserService()->get($user2->id);

        $this->assertNotNull($foundUser);
        $this->assertEquals($user2->name, $foundUser->name);
    }

    /**
     * @group services
     */
    public function test_get_list()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->actingAs($user1);
        $users = $this->getUserService()->getList();

        $this->assertCount(2, $users);
    }

    /**
     * @group services
     */
    public function test_update()
    {
        $user = User::factory()->create();
        $request = UpdateUserRequest::create('/', 'POST', [
            'name' => 'Новое имя',
            'email' => 'new@test.ru',
            'password' => '',
        ]);
        $result = $this->getUserService()->update($user->id, $request);
        $updatedUser = $user->fresh();

        $this->assertTrue($result);
        $this->assertEquals('Новое имя', $updatedUser->name);
    }

    /**
     * @group services
     */
    public function test_create()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $request = UpdateUserRequest::create('/', 'POST', [
            'name' => 'Имя',
            'email' => 'newuser@test.ru',
            'password' => '123456',
        ]);
        $newUser = $this->getUserService()->create($request);

        $this->assertNotNull($newUser);
        $this->assertEquals('newuser@test.ru', $newUser->email);
    }

    /**
     * @group services
     */
    public function test_delete()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->actingAs($user1);
        $result = $this->getUserService()->delete([$user2->id]);

        $this->assertEquals('success', $result['status']);
    }

    private function getUserService()
    {
        return app()->make(UserService::class);
    }
}
