<?php

namespace Tests\Feature\Services;

use App\Services\ProjectService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Permission;
use App\Models\Member;
use App\Models\Project;

class ProjectServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group services
     */
    public function test_dont_get_project()
    {
        $entities = $this->getEntities(null, false);
        $this->actingAs($entities['user']);
        $result = $this->getProjectService()->get($entities['project']->id);

        $this->assertNull($result);
    }

    /**
     * @group services
     */
    public function test_get_project()
    {
        $entities = $this->getEntities();
        $this->actingAs($entities['user']);
        $result = $this->getProjectService()->get($entities['project']->id);

        $this->assertNotNull($result);
    }

    /**
     * @group services
     */
    public function test_get_projects_list()
    {
        $entities = $this->getEntities();
        $this->actingAs($entities['user']);
        $result = $this->getProjectService()->getList();

        $this->assertCount(1, $result);
    }

    /**
     * @group services
     */
    public function test_create_project()
    {
        $data = [
            'name' => 'Тестовый проект',
            'description' => 'Описание тестового проекта',
        ];
        $result = $this->getProjectService()->create($data);

        $this->assertNotNull($result);
        $this->assertEquals('Тестовый проект', $result->name);
    }

    /**
     * @group services
     */
    public function test_get_project_members()
    {
        $entities = $this->getEntities();
        $result = $this->getProjectService()->getProjectMembers($entities['project']);

        $this->assertCount(1, $result);
    }

    /**
     * @group services
     */
    public function test_update_project()
    {
        $entities = $this->getEntities();
        $data = [
            'name' => 'Тестовый проект',
        ];
        $result = $this->getProjectService()->update($entities['project']->id, $data);

        $this->assertTrue($result);
    }

    /**
     * @group services
     */
    public function test_delete_project()
    {
        $entities = $this->getEntities(null, false);
        $result = $this->getProjectService()->delete([$entities['project']->id]);

        $this->assertArrayHasKey('status', $result);
        $this->assertEquals('success', $result['status']);
    }

    private function getProjectService()
    {
        return app()->make(ProjectService::class);
    }

    private function getEntities($permission = null, $withMember = true): array
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

        $project = Project::create([
            'name' => 'Тестовый'
        ]);

        if ($withMember)
        {
            $member = Member::create([
                'user_id' => $user->id
            ]);
            $project->members()->attach($member->id);
        }

        $entities = [
            'user' => $user,
            'project' => $project,
        ];
        if ($withMember)
        {
            $entities['member'] = $member;
        }

        return $entities;
    }
}
