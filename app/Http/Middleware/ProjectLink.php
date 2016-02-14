<?php

namespace App\Http\Middleware;

use Closure;

class ProjectLink
{
    /**
     * Handle an incoming request.
     * Controleert of de actieve gebruiker toegang heeft tot het rapport dat benaderd wordt ($request->id)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $user = \Auth::user();
        //@Todo: netjes maken met een exception
        if($user->hasRole('opdrachtgever')) {
            if (!$user->projects->contains($request->id)) {
                return response()->view('errors.401', [], 401);
            }
        }

        return $next($request);
    }
}
