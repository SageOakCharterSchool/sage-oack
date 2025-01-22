<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */


    public function handle(Request $request, Closure $next)
    {
        //dd(getenv('BLOCK_BY_IP_ACTIVE'));
        if (getenv('BLOCK_BY_IP_ACTIVE')=="Y") {
            if (\Auth::user()->role_as == 1) {
                $blockIps = explode(",",getenv('IPS_ALLOWED'));
                //dd($request->ip(),$blockIps);
                if (!in_array($request->ip(), $blockIps)) {
                    abort(403, "You are restricted to access the site.");
                }
            }
        }
        return $next($request);
    }
}
