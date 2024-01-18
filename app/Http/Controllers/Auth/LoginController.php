<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // ログイン成功後のリダイレクト先
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // ログインに成功した時の処理
    protected function authenticated(Request $request, $user)
    {
        $token = $user->createToken('My API Token')->plainTextToken;

        // トークンをセッションに保存してリダイレクト
        return redirect()->route('api-token-get-redirect')->with('api_token', $token);
    }

    //ログアウト処理をオーバーライドする
    public function logout(Request $request)
    {
        Auth::guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->route('api-token-throw-away-redirect');
    }
}
