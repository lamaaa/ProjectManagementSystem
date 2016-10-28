<?php

namespace App\Http\Middleware;

use Closure;

class CheckManagerLogin
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
        $user = $request->session()->get('user', '');
        if(!$user || $user->role != '管理员')
        {
            return redirect('/');
        }
        return $next($request);
    }
}
