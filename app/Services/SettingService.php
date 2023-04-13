<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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
                    $this->resizeAndSaveLogo($request);
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
                $this->resizeAndSaveLogo($request);
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

    private function resizeAndSaveLogo($request)
    {
        $logo = $request->file('logo');
        $resizedLogo = Image::make($logo->path());
        $resizedLogo->resize(null, 60, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $resizedLogo->save(storage_path('app/public/logo.png'), 100);
    }
}
