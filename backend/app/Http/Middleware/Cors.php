<?php

namespace App\Http\Middleware;

use Closure;

class Cors
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
        $allowedOrigins = explode(' ',config('app.cors_domain'));
        if(isset($_SERVER['HTTP_ORIGIN'])){
            $origin = $_SERVER['HTTP_ORIGIN'];
        }else{
            $origin = parse_url($request->header('Referer'), PHP_URL_HOST);
        }

        if (in_array($origin, $allowedOrigins)) {
            return $next($request)
                ->header('Access-Control-Allow-Origin', $origin)
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE')
                ->header('Access-Control-Allow-Headers', 'Content-Type');
        }

        return $next($request);
    }
}
