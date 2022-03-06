<?php

namespace App\Repositories;

use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Models\Project;

class EloquentProjectRepository implements ProjectRepositoryInterface
{
    public function find(int $id)
    {
        return Project::find($id);
    }

    public function search(array $filter = [])
    {
        return Project::where($filter)->paginate();
    }

    public function searchByMember(int $id, array $filter = [])
    {
        return Project::whereHas('members', function($query) use ($id) {
            $query->where('member_id', $id);
        })->where($filter)->paginate();
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

    public function attachMembers(object $project, array $memberIds = [])
    {
        return $memberIds ? $project->members()->attach($memberIds) : false;
    }
}
