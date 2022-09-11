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

    public function search(array $filter = [], bool $withPaginate = true, array $with = [])
    {
        $query = Project::where($filter);
        if (!empty($with)) $query = $query->with($with);

        if ($withPaginate)
        {
            return $query->paginate();
        }
        else
        {
            return $query->get();
        }
    }

    public function searchByMember(int $id, array $filter = [], bool $withPaginate = true, array $with = [])
    {
        $query = Project::whereHas('members', function($query) use ($id) {
            $query->where('member_id', $id);
        })->where($filter);

        if (!empty($with)) $query = $query->with($with);

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
        $project = Project::create($data);

        return $project;
    }

    public function updateFromArray(int $id, array $data): bool
    {
        $project = $this->find($id);
        $result = $project ? $project->update($data) : false;
        $result = $result ? $project->members()->sync($data['memberIds']) : false;
        return is_array($result) ? true : $result;
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
