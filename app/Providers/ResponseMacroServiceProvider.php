<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     *
     */
    public function boot()
    {
        Response::macro('eshopsunion', function ($code = 200, $data = null, $msg = null) {
            $content = array(
                'code' => $code,
                'data' => $data,
                'msg' => $msg
            );
            return response()->json($content);
        });
    }
}
