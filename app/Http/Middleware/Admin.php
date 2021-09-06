<?php

namespace App\Http\Middleware;

use Auth;
use Route;
use Closure;
use Illuminate\Http\Request;

class Admin
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
        $myData = Auth::guard('admin')->user();
        if ($myData == "") {
            $toRoute = Route::currentRouteName();
            return redirect()->route('admin.loginPage', ['r' => $toRoute])->withErrors([
                'You have to logged in before accessing this page'
            ]);
        }
        return $next($request);
    }
}
