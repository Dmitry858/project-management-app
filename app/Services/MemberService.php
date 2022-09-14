<?php

namespace App\Services;

use App\Repositories\EloquentMemberRepository;
use App\Repositories\Interfaces\MemberRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MemberService
{
    protected $memberRepository;

    public function __construct(MemberRepositoryInterface $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }

    public function get(int $id)
    {
        return $this->memberRepository->find($id);
    }

    public function getList(array $filter = [])
    {
        if (Auth::user()->hasRole('admin'))
        {
            return $this->memberRepository->search($filter);
        }
        else
        {
            return [];
        }
    }

    public function create($data)
    {
        unset($data['_token']);

        return $this->memberRepository->createFromArray($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->memberRepository->updateFromArray($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->memberRepository->delete($id);
    }

    public static function getMemberFullName(int $memberId): string
    {
        $fullName = '';
        if (Cache::has('member_'.$memberId.'_fullname'))
        {
            $fullName = Cache::get('member_'.$memberId.'_fullname');
        }
        else
        {
            $repo = new EloquentMemberRepository;
            $member = $repo->find($memberId);
            if ($member)
            {
                $fullName = $member->user->name.' '.$member->user->last_name;
                Cache::put('member_'.$memberId.'_fullname', $fullName, 86400);
            }
        }

        return trim($fullName);
    }
}
