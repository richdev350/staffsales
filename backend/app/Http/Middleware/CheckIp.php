<?php

namespace App\Http\Middleware;

use Closure;

class CheckIp
{
    public function handle($request, Closure $next)
    {
        $accept_ip = config('app.accept_ip');
        $remote_ip = $_SERVER['REMOTE_ADDR'];

        list($accept_ip, $mask) = explode('/', $accept_ip);
        $accept_long = ip2long($accept_ip) >> (32 - $mask);
        $remote_long = ip2long($remote_ip) >> (32 - $mask);

        if($accept_long == $remote_long){
            return $next($request);
        }else{
            return abort('404');
        }
    }
}
