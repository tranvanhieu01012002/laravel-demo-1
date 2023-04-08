<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdIsInteger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (is_numeric($request->id)) {
            return $next($request);
        } elseif ($request->is('api/*')) {
            return redirect()->route("errors.id.invalidate");
        }
    }
}
