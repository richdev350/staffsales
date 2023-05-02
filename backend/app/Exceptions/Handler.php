<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        // 開発環境では全エラー表示※not foundを除く
        if(config('app.debug')){
            if(get_class($exception) === "Illuminate\Database\Eloquent\ModelNotFoundException"
                || get_class($exception) === "Symfony\Component\HttpKernel\Exception\NotFoundHttpException"){
                if ($request->is('admin/*')) {
                    // 404
                    return response()->view('admins.errors.404', [], 404);
                }else{
                    return response()->view('generals.errors.404', [], 404);
                }
            }else{
                if(!$this->isHttpException($exception)) {
                    return parent::render($request, $exception);
                }
            }
        }

        if($this->isHttpException($exception)) {
            if ($request->is('admin/*')) {
                // 403
                if($exception->getStatusCode() == 403) {
                    return response()->view('admins.errors.403', [], 403);
                }
                // 404
                if($exception->getStatusCode() == 404) {
                    return response()->view('admins.errors.404', [], 404);
                }
                // 500
                return response()->view('admins.errors.500', [], 500);
            } else {
                // 403
                if($exception->getStatusCode() == 403) {
                    return response()->view('generals.errors.403', [], 403);
                }
                // 404
                if($exception->getStatusCode() == 404) {
                    return response()->view('generals.errors.404', [], 404);
                }
                // 503
                if($exception->getStatusCode() == 503) {
                    return response()->view('generals.errors.503', [], 503);
                }
                // 500
                return response()->view('generals.errors.500', [], 500);
            }
        }

        // csrf例外だった場合はログイン画面に飛ばす
        if ($exception instanceof \Illuminate\Session\TokenMismatchException){
            session()->flash('csrfError', true);
            if ($request->is('admin/*')) {
                return redirect()->to('admin/login');
            }else{
                // 管理画面以外はTOPへ
                return redirect()->to('home');
            }
        }

        if ($request->is('admin/*')) {
            if(get_class($exception) === "Illuminate\Database\Eloquent\ModelNotFoundException"){
                return response()->view('admins.errors.404', [], 404);
            }else{
                return response()->view('admins.errors.500', [], 500);
            }
        }else{
            if(get_class($exception) === "Illuminate\Database\Eloquent\ModelNotFoundException"){
                return response()->view('generals.errors.404', [], 404);
            }else{
                return response()->view('generals.errors.500', [], 500);
            }
        }
    }
}
