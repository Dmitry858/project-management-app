<?php

namespace App\Repositories;

use App\Repositories\Interfaces\MemberRepositoryInterface;
use App\Models\Member;
use App\Traits\EloquentQueryBuilderHelper;

class EloquentMemberRepository implements MemberRepositoryInterface
{
    use EloquentQueryBuilderHelper;

    public function find(int $id)
    {
        return Member::find($id);
    }

    public function search(array $filter = [], bool $withPaginate = true, array $with = [])
    {
        $query = Member::query();
        if (count($filter) > 0)
        {
            if (array_key_exists('project_id', $filter))
            {
                $filter['has'] = [
                    'projects',
                    'project_id',
                    $filter['project_id'],
                ];
                unset($filter['project_id']);
            }
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
        if (array_key_exists('project_ids', $data))
        {
            $projectIds = $data['project_ids'];
            unset($data['project_ids']);
        }

        $member = Member::create($data);
        if ($member && isset($projectIds))
        {
            $member->projects()->attach($projectIds);
        }

        return $member;
    }

    public function updateFromArray(int $id, array $data): bool
    {
        $member = $this->find($id);

        if (!$member) return false;

        if (array_key_exists('project_ids', $data))
        {
            $projectIds = $data['project_ids'];
            unset($data['project_ids']);
        }
        $result = $member->update($data);

        if ($result && isset($projectIds))
        {
            $member->projects()->sync($projectIds);
        }
        elseif ($result && !isset($projectIds))
        {
            $member->projects()->detach();
        }

        return $result;
    }

    public function delete(array $ids): bool
    {
        $members = $this->search(['id' => $ids], false);

        if (count($members) > 0)
        {
            foreach ($members as $member)
            {
                if (count($member->projects) > 0)
                {
                    $member->projects()->detach();
                }
            }
            $result = Member::destroy($ids);
        }
        else
        {
            $result = false;
        }

        return $result;
    }
}
