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

        if (date("w") == 0 and \App::environment('productie') and request()->ip() != '37.188.77.197') {
            include(public_path("index_static.html"));
            die();
        }

        return $next($request);
    }
}
