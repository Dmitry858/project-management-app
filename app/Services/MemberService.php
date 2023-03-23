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

    public function getList(array $filter = [], bool $withPaginate = true, array $with = [])
    {
        return $this->memberRepository->search($filter, $withPaginate, $with);
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

    public function delete(array $ids): array
    {
        if (isset($ids['ids']) && is_array($ids['ids'])) $ids = $ids['ids'];

        $members = $this->memberRepository->search(['id' => $ids], false);

        if (count($members) === 0)
        {
            return [
                'status' => 'error',
                'text' => __('errors.member_not_found')
            ];
        }

        $success = $this->memberRepository->delete($ids);

        $successMsg = count($ids) > 1 ? __('flash.members_deleted') : __('flash.member_deleted');

        return [
            'status' => $success ? 'success' : 'error',
            'text' => $success ? $successMsg : __('flash.general_error')
        ];
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
