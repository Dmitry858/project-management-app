<?php

namespace App\Services;

use App\Repositories\Interfaces\MemberRepositoryInterface;
use Illuminate\Support\Facades\Auth;

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
}
