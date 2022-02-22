<?php

namespace App\Repositories;

use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Models\Project;

class EloquentProjectRepository implements ProjectRepositoryInterface
{
    public function find(int $id)
    {
        $project = Project::find($id);

        return $project;
    }

    public function search(array $filter = [])
    {
        return Project::paginate();
    }

    public function createFromArray(array $data)
    {
        $project = Project::create($data);

        return $project;
    }

    public function updateFromArray(int $id, array $data): bool
    {
        $project = $this->find($id);

        return $project ? $project->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $project = $this->find($id);

        return $project ? $project->delete() : false;
    }
}
