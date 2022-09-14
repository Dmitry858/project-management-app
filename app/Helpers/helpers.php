<?php

if (!function_exists('membersListHtml')) {
    function membersListHtml(object $members): string
    {
        $html = '';

        if ($members->count() > 0)
        {
            foreach ($members as $member)
            {
                $html .= '<p>'.\App\Services\MemberService::getMemberFullName($member->id).'</p>';
            }
        }

        return $html;
    }
}
