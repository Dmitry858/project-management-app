<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $title = __('titles.settings');

        return view('settings', compact('title'));
    }

    public function update(Request $request)
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

        return redirect()->route('settings')->with($flashKey, $flashValue);
    }
}
