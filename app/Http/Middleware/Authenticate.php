<?php

namespace App\Http\Middleware;

use Closure;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{

    public function handle($request, Closure $next, ...$guards)
    {
        $requestToken = $request->header('authorization');
        $personalAccessToken = new PersonalAccessToken();
        $token = $personalAccessToken->findToken(str_replace('Bearer', '', $requestToken));
        if($token == null) return redirect('home');

        return $next($request);
    }
}
