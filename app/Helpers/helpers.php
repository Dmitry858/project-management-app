<?php

if (!function_exists('membersListHtml')) {
    function membersListHtml(object $members): string
    {
        $html = '';

        if ($members->count() > 0)
        {
            foreach ($members as $member)
            {
                $html .= '<p>'.$member->user->name.' '.$member->user->last_name.'</p>';
            }
        }

        return $html;
    }
}
