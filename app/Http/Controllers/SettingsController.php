<?php

namespace App\Http\Controllers;

class SettingsController extends Controller
{
    public function account()
    {
        // アカウント設定に関する処理
        return view('settings.account');
    }

    public function publicProfile()
    {
        // 公開プロフィール設定に関する処理
        return view('settings.public-profile');
    }
}
