<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

use App\Http\Controllers\Controller;

class AccountController extends Controller
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

    // アカウント設定のページを表示
    public function index(User $user)
    {
        if(!$this->userCheck($user)){
            return response('You cannot view this page.', 403);
        }

        return view('settings.account', compact('user'));
    }

    // アカウント設定の更新
    public function update(Request $request, User $user)
    {
        Log::info('CCCCCCCCCCCCCCCCCCCCCCCC');
        if ($request["password"] !== $request["password_confirmation"]){
            Log::info('XXXXXXXXXXXXXXXXXX');
            return back()->with('error', 'パスワードとパスワード確認の入力が一致しません。');
        }
    
        // バリデーションルールを定義
        $validateData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:4',
            'icon_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);
    
        // アイコンの画像をディスクに保存
        if ($request->hasFile('icon_image')) {
            //public/icons ではなくて、 storage/app/public/icons ディレクトリに格納される。
            $iconPath = $request->file('icon_image')->store('icons', 'public');
            $validateData['icon_image'] = $iconPath;
        }

        // パスワードが空の場合、配列から削除
        if (empty($validateData['password'])) {
            unset($validateData['password']);
        }

        $user->update($validateData);

        return redirect()->route('settings.account', $user->id)->with('success', 'アカウント設定が更新されました。');
    }
}
