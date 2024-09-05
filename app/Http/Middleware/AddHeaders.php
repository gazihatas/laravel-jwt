<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->headers->has('Accept') || $request->header('Accept') !== 'application/json') {
            $request->headers->set('Accept', 'application/json');
        }

        if (!$request->headers->has('Content-Type') || $request->header('Content-Type') !== 'application/json') {
            $request->headers->set('Content-Type', 'application/json');
        }

        return $next($request);
    }

}
