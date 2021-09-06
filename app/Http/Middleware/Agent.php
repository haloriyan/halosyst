<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class Agent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $myData = Auth::guard('agent')->user();
        if ($myData == "") {
            return redirect()->route("agent.loginPage");
        }
        return $next($request);
    }
}
