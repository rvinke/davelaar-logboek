<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Bugsnag\BugsnagLaravel\BugsnagExceptionHandler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Illuminate\Validation\ValidationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {

        if (config('app.debug') && app()->environment() != 'testing') {
            return $this->renderExceptionWithWhoops($request, $e);
        }


        if ($this->isHttpException($e)) {
            return $this->renderHttpException($e);
        } else {
            return response()->view('errors.500', [], 500);
        }


        //return parent::render($request, $e);

        /*if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        return parent::render($request, $e);*/
    }

    /**
     * Render an exception using Whoops.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Response
     */
    protected function renderExceptionWithWhoops($request, Exception $e)
    {
        $whoops = new \Whoops\Run;

        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());

        return new \Illuminate\Http\Response(
            $whoops->handleException($e),
            $e->getStatusCode(),
            $e->getHeaders()
        );
    }
    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $e
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $e)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        } else {
            return redirect()->guest('login');
        }
    }
}
