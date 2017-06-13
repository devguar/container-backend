<?php

namespace Devguar\OContainer\Permissions;

use Closure;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $regra)
    {
        $regras = explode('|',$regra);
        PermissionsControl::hasPermissionOrAbort($regras);
        return $next($request);
    }
}
