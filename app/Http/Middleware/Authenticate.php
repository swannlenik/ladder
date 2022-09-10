<?php

namespace App\Http\Middleware;

use App\Http\Services\AccessRightsService;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards) {
        /** @var AccessRightsService $ars */
        $ars = app()->make(AccessRightsService::class);
        $user = auth()->user();

        if (isset($user)) {
            $routesAllowed = array_keys($ars->getRightsByUserId($user->getAuthIdentifier()));
            if ($request->route()->getName() === 'logout' || in_array($request->route()->getName(), $routesAllowed)) {
                return $next($request);
            }
        }
        return redirect()->route('login');
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
