<?php

namespace App\Http\Middleware;

use Closure;
use Menu;

class SundayMiddleware
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure                  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(date("w") == 0 AND \App::environment('productie')){
            include(public_path("index_static.html"));
            die();
        }

        return $next($request);
    }
}