<?php

namespace Tests\Feature\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\User;

class EloquentUserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private function getUserRepository(): UserRepositoryInterface
    {
        return app()->make(UserRepositoryInterface::class);
    }

    /**
     * @group repositories
     */
    public function test_find_user()
    {
        $user = User::factory()->create();
        $result = $this->getUserRepository()->find($user->id);

        $this->assertNotNull($result);
    }

    /**
     * @group repositories
     */
    public function test_find_or_create_member()
    {
        $user = User::factory()->create();
        $result = $this->getUserRepository()->findOrCreateMember($user);

        $this->assertNotNull($result);
    }

    /**
     * @group repositories
     */
    public function test_search_users()
    {
        $user = User::factory()->create();
        $result = $this->getUserRepository()->search([
            'email' => $user->email
        ]);

        $this->assertSame(1, count($result));
    }

    /**
     * @group repositories
     */
    public function test_create_user()
    {
        $user = User::factory()->make();
        $result = $this->getUserRepository()->createFromArray([
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
        ]);

        $this->assertNotNull($result);
        $this->assertDatabaseHas('users', [
            'email' => $user->email,
        ]);
    }

    /**
     * @group repositories
     */
    public function test_update_user()
    {
        $user = User::factory()->create();
        $result = $this->getUserRepository()->updateFromArray($user->id, [
            'email' => 'new_email@yandex.ru'
        ]);

        $this->assertTrue($result);
        $this->assertDatabaseHas('users', [
            'email' => 'new_email@yandex.ru',
        ]);
    }

    /**
     * @group repositories
     */
    public function test_delete_user()
    {
        $user = User::factory()->create();
        $result = $this->getUserRepository()->delete([$user->id]);

        $this->assertTrue($result);
        $this->assertModelMissing($user);
    }
}
