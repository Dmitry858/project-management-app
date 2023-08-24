<?php

namespace App\Repositories;

use App\Repositories\Interfaces\AttachmentRepositoryInterface;
use App\Models\Attachment;
use App\Traits\EloquentQueryBuilderHelper;

class EloquentAttachmentRepository implements AttachmentRepositoryInterface
{
    use EloquentQueryBuilderHelper;

    public function find(int $id)
    {
        return Attachment::find($id);
    }

    public function search(array $filter = [])
    {
        $query = Attachment::query();
        if (count($filter) > 0)
        {
            $query = $this->handleFilter($query, $filter);
        }
        return $query->get();
    }

    public function createFromArray(array $data)
    {
        return Attachment::insert($data);
    }

    public function delete(array $ids)
    {
        return Attachment::whereIn('id', $ids)->delete();
    }
}
