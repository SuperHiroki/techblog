<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    // アカウント設定ページを表示
    public function index(User $user)
    {
        return view('settings.account', compact('user'));
    }

    // アカウント設定の更新
    public function update(Request $request, User $user)
    {
        // バリデーションルールを定義
        $validateData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            // その他必要なフィールドをここに追加
        ]);

        // ユーザーデータの更新
        $user->update($validateData);

        // 更新後のリダイレクト
        return redirect()->route('settings.account', $user->id)
                         ->with('success', 'アカウント設定が更新されました。');
    }
}
