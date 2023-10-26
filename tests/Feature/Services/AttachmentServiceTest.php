<?php

namespace Tests\Feature\Services;

use App\Services\AttachmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Member;
use App\Models\Task;
use App\Models\Attachment;

class AttachmentServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group services
     */
    public function test_get_path()
    {
        $entities = $this->getEntities();
        $this->actingAs($entities['user']);
        $path = $this->getAttachmentService()->getPath($entities['attachment']->id);

        $this->assertNotNull($path);
    }

    /**
     * @group services
     */
    public function test_get_path_without_permission()
    {
        $entities = $this->getEntities(true);
        $this->actingAs($entities['user']);
        $path = $this->getAttachmentService()->getPath($entities['attachment']->id);

        $this->assertNull($path);
    }

    private function getAttachmentService()
    {
        return app()->make(AttachmentService::class);
    }

    private function getEntities($withoutPerm = false): array
    {
        $user = User::factory()->create();

        $member = Member::create([
            'user_id' => $user->id
        ]);

        $task = Task::create([
            'name' => 'Тестовая задача',
            'owner_id' => $withoutPerm ? $member->id + 1 : $member->id,
            'responsible_id' => $withoutPerm ? $member->id + 1 : $member->id,
            'stage_id' => 1,
            'project_id' => 1,
        ]);

        $attachment = Attachment::create([
            'file_name' => 'Тест',
            'file' => 'path/to/file.txt',
            'task_id' => $task->id,
        ]);

        $entities = [
            'user' => $user,
            'attachment' => $attachment,
        ];

        return $entities;
    }
}
