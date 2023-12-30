<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\UserProfile;

use App\Http\Controllers\Controller;

class PublicProfileController extends Controller
{
    public function index(User $user)
    {
        $profile = $user->profile()->first() ?? new UserProfile(['user_id' => $user->id]);

        Log::info('WWWWWWWWWWWWWWWWWWWWWWWWWW' . json_encode($profile));

        return view('settings.public-profile', compact('user', 'profile'));
    }

    public function update(Request $request, User $user)
    {
        $profile = $user->profile()->first() ?? new UserProfile(['user_id' => $user->id]);
    
        $validateData = $request->validate([
            "public_email" => 'nullable|email|max:255', // email バリデーションを追加
            "github" => 'nullable|max:255',
            "website" => 'nullable|url|max:255', // URL バリデーションを追加
            "organization" => 'nullable|max:255',
            "location" => 'nullable|max:255',
            "bio" => 'nullable|max:1000', // テキストエリア用に最大1000文字
            "sns1" => 'nullable|max:255',
            "sns2" => 'nullable|max:255',
            "sns3" => 'nullable|max:255',
            "sns4" => 'nullable|max:255',
            "sns5" => 'nullable|max:255',
            "sns6" => 'nullable|max:255'
        ]);

        Log::info('FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF' . json_encode($profile));
        Log::info('GGGGGGGGGGGGGGGGGGGGGGGGGGG' . json_encode($validateData));
        
        $profile->update($validateData);
        
        return redirect()->route('settings.public-profile', $user->id)
                         ->with('success', 'プロフィールが更新されました。');
    }    
}
