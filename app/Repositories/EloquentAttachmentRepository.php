<?php

namespace App\Repositories;

use App\Repositories\Interfaces\AttachmentRepositoryInterface;
use App\Models\Attachment;

class EloquentAttachmentRepository implements AttachmentRepositoryInterface
{
    public function find(int $id)
    {
        return Attachment::find($id);
    }

    public function search(array $filter = [])
    {
        $query = Attachment::query();
        if (count($filter) > 0)
        {
            foreach ($filter as $key => $value)
            {
                if (is_array($value))
                {
                    $query = $query->whereIn($key, $value);
                }
                else
                {
                    $query = $query->where($key, $value);
                }
            }
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
