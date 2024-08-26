<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SwaggerAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->getUser() != env('SWAGGER_DOCS_USER', 'tester')|| $request->getPassword() != env('SWAGGER_DOCS_PASSWORD', '123456')) {
            return response('Unauthorized.', 401)
                ->header('WWW-Authenticate', 'Basic realm="Swagger UI"');
        }

        return $next($request);
    }
}
