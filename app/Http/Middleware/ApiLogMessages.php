<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log; 

class ApiLogMessages
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
        Log::debug($request->method());
        return $next($request);
    }

    public function terminate($request,$response){
        Log::debug($response->status());
        if ($response->status()>=400) {
            Log::error("An error has encountered during the operation.");
        }elseif ($response->status()>=200 && $response->status()<300) {
            Log::info("the operation was succeed.");
        }

    }
}
