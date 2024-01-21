<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Exception;

use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////カスタムメソッド
    //認証失敗
    public function render($request, Throwable $exception)
    {
        Log::info('RRRRRRRRRRRRRRRRRRRRRRRRR Handker Error.');

        if ($exception instanceof AuthenticationException ) {

            Log::info('GGGGGGGGGGGGGGGGGG AuthenticationException');
            return response()->json([
             'message' => 'ログインしていません。'
            ],401);
        }
    
        return parent::render($request, $exception);
    }
    
}
