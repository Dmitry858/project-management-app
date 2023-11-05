<?php

namespace App\Repositories;

use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Models\Comment;
use App\Traits\EloquentQueryBuilderHelper;

class EloquentCommentRepository implements CommentRepositoryInterface
{
    use EloquentQueryBuilderHelper;

    public function find(int $id)
    {
        return Comment::find($id);
    }

    public function search(array $filter = [], bool $withPaginate = true)
    {
        $query = Comment::query();

        if (count($filter) > 0)
        {
            $query = $this->handleFilter($query, $filter);
        }

        if ($withPaginate)
        {
            return $query->paginate();
        }
        else
        {
            return $query->get();
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

    public function delete(array $ids): bool
    {
        $result = Comment::destroy($ids);

        return boolval($result);
    }
}
