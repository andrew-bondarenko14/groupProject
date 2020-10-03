<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Cookie;

class SaveLastRequestTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->user()) {
        $res = $next($request);
        return $res->cookie('LastRequestTime', Carbon::now(), strtotime( '+30 days' ));
        }
        return $res;
    }
}
