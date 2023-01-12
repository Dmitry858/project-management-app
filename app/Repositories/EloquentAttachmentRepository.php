<?php

namespace App\Repositories;

use App\Repositories\Interfaces\AttachmentRepositoryInterface;
use App\Models\Attachment;

class EloquentAttachmentRepository implements AttachmentRepositoryInterface
{
    public function search(array $filter = [])
    {
        return Attachment::where($filter)->get();
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
