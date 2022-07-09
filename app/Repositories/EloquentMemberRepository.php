<?php

namespace App\Repositories;

use App\Repositories\Interfaces\MemberRepositoryInterface;
use App\Models\Member;

class EloquentMemberRepository implements MemberRepositoryInterface
{
    public function find(int $id)
    {
        return Member::find($id);
    }

    public function search(array $filter = [], bool $withPaginate = true)
    {
        $query = Member::query();
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
        if ($member && $projectIds)
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

        if ($result && $projectIds)
        {
            $member->projects()->sync($projectIds);
        }

        return $result;
    }

    public function delete(int $id): bool
    {
        $member = $this->find($id);

        if ($member)
        {
            if (count($member->projects) > 0)
            {
                $member->projects()->detach();
            }
            $result = $member->delete();
        }
        else
        {
            $result = false;
        }

        return $result;
    }
}
