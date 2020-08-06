<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Response;

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
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function($data, $msg = 'Success', $statusCode = 200){
            return Response::json([
                'success' => true,
                'errors' => null,
                'data' => $data,
                'message' => $msg
            ], $statusCode);
        });

        Response::macro('error', function($error, $statusCode = 400){
            return Response::json([
                'success' => false,
                'errors' => $error,
                'data' => null,
            ], $statusCode);
        });
    }
}
