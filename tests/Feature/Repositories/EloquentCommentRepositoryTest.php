<?php

namespace Tests\Feature\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Models\Comment;

class EloquentCommentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private function getCommentRepository(): CommentRepositoryInterface
    {
        return app()->make(CommentRepositoryInterface::class);
    }

    /**
     * @group repositories
     */
    public function test_find_comment()
    {
        $comment = Comment::factory()->create();
        $result = $this->getCommentRepository()->find($comment->id);

        $this->assertNotNull($result);
        $this->assertEquals($comment->comment_text, $result->comment_text);
    }

    /**
     * @group repositories
     */
    public function test_search_comments()
    {
        Comment::factory()->count(2)->create([
            'task_id' => 1
        ]);
        Comment::factory()->count(3)->create([
            'task_id' => 2
        ]);
        $result = $this->getCommentRepository()->search(['task_id' => 2]);

        $this->assertCount(3, $result);
    }

    /**
     * @group repositories
     */
    public function test_create_comment()
    {
        $result = $this->getCommentRepository()->createFromArray([
            'comment_text' => 'Какой-то текст',
            'task_id' => 1,
            'member_id' => 1,
        ]);

        $this->assertNotNull($result);
        $this->assertDatabaseHas('comments', [
            'comment_text' => 'Какой-то текст',
        ]);
    }

    /**
     * @group repositories
     */
    public function test_update_comment()
    {
        $comments = Comment::factory()->count(2)->create();
        $commentId = $comments[0]->id;

        $result = $this->getCommentRepository()->updateFromArray($commentId, [
            'comment_text' => 'Отредактированный комментарий'
        ]);

        $this->assertTrue($result);
        $this->assertDatabaseHas('comments', [
            'comment_text' => 'Отредактированный комментарий'
        ]);
    }

    /**
     * @group repositories
     */
    public function test_delete_comment()
    {
        $comment = Comment::factory()->create();
        $result = $this->getCommentRepository()->delete([$comment->id]);

        $this->assertTrue($result);
        $this->assertModelMissing($comment);
    }
}
