<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Ip2Region;

class GetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(Request $request, Closure $next)
    {
//        $ip2region = new Ip2Region();
//        $ipAddress = $request->ip();
//        $info = $ip2region->btreeSearch($ipAddress);
//        $regionInfo = explode("|", $info['region']);
//        $region = $regionInfo[0];
//        if ($region == '中国') {
//            $request->site_id = 1;
//        } elseif ($region == '日本') {
//            $request->site_id = 3;
//        } else {
            $request->site_id = 1;
//        }
        return $next($request);
    }
}
