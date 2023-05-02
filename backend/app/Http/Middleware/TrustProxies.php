<?php

namespace App\Http\Middleware;

use Fideloper\Proxy\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string
     */
    // protected $proxies;
    protected $proxies = [
        '13.114.48.82',
        '18.178.193.4',
        '18.178.44.229',
        '52.197.139.7',
        '13.230.18.58',
        '3.113.200.126',
        '52.193.180.251',
    ];

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_FOR;
}
