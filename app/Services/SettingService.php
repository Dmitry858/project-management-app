<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingService
{
    public function updateGeneral(Request $request): array
    {
        $success = true;

        if (Storage::disk('public')->exists('logo.png') && (!$request->input('logo_exists') || $request->hasFile('logo')))
        {
            Storage::disk('public')->delete('logo.png');
            if ($request->hasFile('logo'))
            {
                if ($request->file('logo')->isValid())
                {
                    $request->file('logo')->storeAs('public', 'logo.png');
                }
                else
                {
                    $success = false;
                }
            }
        }
        elseif($request->hasFile('logo'))
        {
            if ($request->file('logo')->isValid())
            {
                $request->file('logo')->storeAs('public', 'logo.png');
            }
            else
            {
                $success = false;
            }
        }

        return [
            'status' => $success ? 'success' : 'error',
            'text' => $success ? __('flash.general_settings_updated') : __('flash.general_error')
        ];
    }
}
