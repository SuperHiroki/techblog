<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\UserProfile;

use App\Http\Controllers\Controller;

class PublicProfileController extends Controller
{
    //もちろんログインしている必要がある。
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                return response('Please login.', 403);
            }

            return $next($request);
        });
    }

    //設定ページなので、その人のみアクセス可能です。
    private function userCheck(User $user){
        if($user->id !== Auth::user()->id){
            return false;
        }else{
            return true;
        }
    }

    //公開プロフィールの設定ページ
    public function index(User $user)
    {
        if(!$this->userCheck($user)){
            return response('You cannot view this page.', 403);
        }

        $profile = $user->profile()->first() ?? new UserProfile(['user_id' => $user->id]);

        return view('settings.public-profile', compact('user', 'profile'));
    }

    public function update(Request $request, User $user)
    {
        $profile = $user->profile()->first() ?? new UserProfile(['user_id' => $user->id]);
    
        $validateData = $request->validate([
            "name" => 'nullable|max:255',
            "public_email" => 'nullable|email|max:255',
            "github" => 'nullable|max:255',
            "website" => 'nullable|url|max:255',
            "organization" => 'nullable|max:255',
            "location" => 'nullable|max:255',
            "bio" => 'nullable|max:1000',
            "sns1" => 'nullable|max:255',
            "sns2" => 'nullable|max:255',
            "sns3" => 'nullable|max:255',
            "sns4" => 'nullable|max:255',
            "sns5" => 'nullable|max:255',
            "sns6" => 'nullable|max:255'
        ]);
        
        $profile->update($validateData);
        
        return redirect()->route('settings.public-profile', $user->id)->with('success', 'プロフィールが更新されました。');
    }    
}
