<?php

use Illuminate\Auth\AuthenticationException;

class Handler
{

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Or redirect to any other route you prefer
        return redirect('/your-custom-login-route');
    }
}
