<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LogRequestId
{
    public function handle(Request $request, Closure $next): Response
    {
        $requestId = $request->headers->get('X-Request-Id', (string) Str::uuid());
        app()->instance('request_id', $requestId);
        $response = $next($request);
        $response->headers->set('X-Request-Id', $requestId);
        return $response;
    }
}


