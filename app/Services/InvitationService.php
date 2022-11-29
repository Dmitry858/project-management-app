<?php

namespace App\Services;

use App\Repositories\Interfaces\InvitationRepositoryInterface;

class InvitationService
{
    protected $invitationRepository;

    public function __construct(InvitationRepositoryInterface $invitationRepository)
    {
        $this->invitationRepository = $invitationRepository;
    }

    public function get(int $id)
    {
        return $this->invitationRepository->find($id);
    }

    public function getValidInvitation(int $id)
    {
        $invitation = $this->get($id);

        if (!$invitation) return false;

        return $invitation->isExpired() ? false : $invitation;
    }

    public function getValidInvitationByKey(string $key)
    {
        $invitation = $this->invitationRepository->findByKey($key);

        if (!$invitation) return false;

        return $invitation->isExpired() ? false : $invitation;
    }

    public function getList(array $filter = [])
    {
        return $this->invitationRepository->search($filter);
    }

    public function create($data)
    {
        unset($data['_token']);

        $success = $this->invitationRepository->createFromArray($data);

        return [
            'status' => $success ? 'success' : 'error',
            'text' => $success ? __('flash.invitation_created') : __('flash.general_error')
        ];
    }

    public function delete(int $id): array
    {
        $success = $this->invitationRepository->delete($id);

        return [
            'status' => $success ? 'success' : 'error',
            'text' => $success ? __('flash.invitation_deleted') : __('flash.general_error')
        ];
    }
}
