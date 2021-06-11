<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class Logging
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Log::info(
            sprintf('[REQUEST %s] %s', $request->method(), $request->url()),
            [$request->all(), $request->headers->all()]
        );
        return $next($request);
    }
}
