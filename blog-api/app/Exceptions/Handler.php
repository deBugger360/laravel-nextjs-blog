<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response; // Required for the original hint
use Illuminate\Http\JsonResponse; // New import needed

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

    /**
     * Convert an authentication exception into a response.
     *
     * Note: In older Laravel/PHP versions, the return type might need to be
     * just `Response`. This signature works best for recent versions.
     */
    protected function unauthenticated($request, AuthenticationException $exception): Response|JsonResponse
    {
        // Check if the request is an API request
        if ($request->expectsJson() || $request->is('api/*')) {
            // This returns Illuminate\Http\JsonResponse
            return response()->json(['message' => $exception->getMessage()], 401);
        }

        // This returns Illuminate\Http\RedirectResponse, which inherits from Response
        return redirect()->guest(route('login'));
    }
}