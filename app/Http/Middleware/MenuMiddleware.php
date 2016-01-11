<?php

namespace App\Http\Middleware;

use Closure;
use Menu;

class MenuMiddleware
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

        $user = \Auth::user();

        if(!empty($user)) {
            Menu::make('example', function($menu) use ($user) {
                $menu->add('Home', '')->icon('fa fa-th')->active('');
                $menu->add('Logboek', 'logboek')->icon('fa fa-archive')->active('projecten/*');
                if($user->hasRole('admin')) $menu->logboek->add('Projecten', \URL::route('projecten.index'))->active('projecten');
                $menu->logboek->add('Rapporten', \URL::route('projecten.rapporten'))->active('projecten/rapporten');

                if($user->hasRole(['admin', 'medewerker'])) {
                    $menu->add('Subdatabase', 'subdatabase')->icon('fa fa-cubes')->active('subdatabase/*');
                    $menu->subdatabase->add('Klanten', 'subdatabase/client')->active('subdatabase/client');
                    $menu->subdatabase->add('Brandkleppen', 'subdatabase/firedamper')->active('subdatabase/firedamper');
                    $menu->subdatabase->add('Bouwlagen', 'subdatabase/floor')->active('subdatabase/floor');
                    $menu->subdatabase->add('Doorvoertypes', 'subdatabase/passthroughType')->active('subdatabase/passthroughType');
                    $menu->subdatabase->add('Systemen', 'subdatabase/system')->active('subdatabase/system');
                    $menu->subdatabase->add('Locaties', 'subdatabase/location')->active('subdatabase/location');
                }

                if($user->hasRole('admin')) {
                    $menu->add('Gebruikers', 'gebruikers')->icon('fa fa-user')->active('user/*');
                    $menu->gebruikers->add('Gebruikers', 'user/')->active('user/');
                }
            });
        }


        return $next($request);
    }
}