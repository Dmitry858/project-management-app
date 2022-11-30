<?php

namespace App\Services;

use App\Repositories\Interfaces\InvitationRepositoryInterface;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationSent;

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

    public function send(int $id): array
    {
        $invitation = $this->invitationRepository->find($id);

        if (!$invitation)
        {
            return [
                'status' => 'error',
                'text' => __('errors.invitation_not_found')
            ];
        }

        if ($invitation->isExpired())
        {
            return [
                'status' => 'error',
                'text' => __('errors.invitation_is_expired')
            ];
        }

        $link = config('app.url').'/register/'.$invitation->secret_key;
        Mail::to($invitation->email)->send(new InvitationSent($link));

        $this->invitationRepository->updateSendingStatus($id, 1);

        return [
            'status' => 'success',
            'text' => __('success_messages.invitation_sent')
        ];
    }
}
