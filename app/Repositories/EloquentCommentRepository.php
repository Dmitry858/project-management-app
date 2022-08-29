<?php

namespace App\Repositories;

use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Models\Comment;

class EloquentCommentRepository implements CommentRepositoryInterface
{
    public function find(int $id)
    {
        return Comment::find($id);
    }

    public function search(array $filter = [], bool $withPaginate = true)
    {
        if ($withPaginate)
        {
            return Comment::where($filter)->paginate();
        }
        else
        {
            return Comment::where($filter)->get();
        }
    }

    public function createFromArray(array $data)
    {
        return Comment::create($data);
    }

    public function updateFromArray(int $id, array $data): bool
    {
        $comment = $this->find($id);

        return $comment ? $comment->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $comment = $this->find($id);

        return $comment ? $comment->delete() : false;
    }
}
