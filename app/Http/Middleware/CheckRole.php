<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$role): Response
    {
        // return $next($request);
        if (in_array($request->user()->role, $role)) {
            return $next($request);
        }
        
        return response()->json([
            'success' => false,
            'code' => 403,
            'message' => 'Anda Seharusnya Tidak Mengakses Ini!'
        ], 403);

    }
}
