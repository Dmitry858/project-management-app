<?php

namespace App\Repositories;

use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Models\Project;
use App\Traits\EloquentQueryBuilderHelper;

class EloquentProjectRepository implements ProjectRepositoryInterface
{
    use EloquentQueryBuilderHelper;

    public function find(int $id)
    {
        return Project::find($id);
    }

    public function search(array $filter = [], bool $withPaginate = true, array $with = [])
    {
        $query = Project::query();
        if (count($filter) > 0)
        {
            $query = $this->handleFilter($query, $filter);
        }

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
        });
        if (count($filter) > 0)
        {
            $query = $this->handleFilter($query, $filter);
        }

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

    public function delete(array $ids): bool
    {
        $projects = $this->search(['id' => $ids], false);

        if (count($projects) > 0)
        {
            $result = Project::destroy($ids);
        }
        else
        {
            $result = false;
        }

        return $result;
    }

    public function attachMembers(object $project, array $memberIds = [])
    {
        return $memberIds ? $project->members()->attach($memberIds) : false;
    }

    public function detachAllMembers(object $project)
    {
        return $project->members()->detach();
    }
}
