<?php

namespace Acelle\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Closure;
use Throwable;
use Acelle\Library\Notification\BackendError as BackendErrorNotification;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App;
use Illuminate\Queue\MaxAttemptsExceededException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        // Currently, Acelle allows user to "cancel" a job by deleting its monitor model, resulting in
        // an "ModelNotFoundException" exception
        // Suppress it here to avoid a log recorded to admin notification area
        ModelNotFoundException::class,

        // For this type of issue, it is normally because the related process is terminated by the OS
        // and the error is already logged to the related campaign, verification process, etc.
        // We do not need to record it here
        MaxAttemptsExceededException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        if (in_array(get_class($exception), $this->dontReport)) {

            // Do nothing
        } elseif (App::runningInConsole() && isInitiated()) {
            try {
                // IMPORTANT: do not use Model here, what if DB connect is not initiated correctly
                // resulting in "exception loop stack" => "PHP Fatal error:  Uncaught Error: Maximum function nesting level of '256' reached, aborting"
                $title = 'PHP CLI ERROR';
                BackendErrorNotification::cleanupDuplicateNotifications($title); // keep last error only, otherwise, get overwhelmed
                BackendErrorNotification::warning([
                    'title' => $title,
                    'message' => sprintf("[%s] [%s] %s: %s", get_current_user(), date('Y-m-d H:i:s eP'), get_class($exception), $exception->getMessage()),
                    'debug' => $exception->getTraceAsString(),
                ], false); // false: also clean up other notification logged by BackendErrorNotification
            } catch (Throwable $t) {
                // just keep silent in case of DB connection issue, cannot write to notification table
            }
        }

        parent::report($exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $e
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e)
    {
        // With 404 error, no way to use response()->view
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            // Default to
            // return view('errors.404');
        }

        return parent::render($request, $e);
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
