<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Services\SettingService;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index()
    {
        $title = __('titles.settings');

        return view('settings.index', compact('title'));
    }

    public function indexGeneral()
    {
        $title = __('titles.general_settings');

        return view('settings.general', compact('title'));
    }

    public function updateGeneral(Request $request)
    {
        $result = $this->settingService->updateGeneral($request);

        return redirect()->route('settings.general')->with($result['status'], $result['text']);
    }

    public function indexCaching()
    {
        $title = __('titles.caching');

        return view('settings.caching', compact('title'));
    }

    public function updateCaching(Request $request)
    {
        $data = $request->all();
        if (isset($data['clear_cache']) && $data['clear_cache'])
        {
            Cache::flush();
            $flashKey = 'success';
            $flashValue = __('flash.clear_cache_completed');
        }
        else
        {
            $flashKey = 'error';
            $flashValue = __('flash.general_error');
        }

        return redirect()->route('settings.caching')->with($flashKey, $flashValue);
    }
}
