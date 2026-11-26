<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReadOnlyDemo
{
    public function handle(Request $request, Closure $next): Response
    {
        if (config('opus.read_only') && ! in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)) {
            abort(403, 'A demonstração pública do OPUS é apenas de consulta.');
        }

        return $next($request);
    }
}
