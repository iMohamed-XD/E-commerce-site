<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSeller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isSeller()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. Seller access required.'], 403);
            }
            
            return redirect()->route('dashboard')->with('error', 'هذا القسم مخصص للبائعين فقط.');
        }

        return $next($request);
    }
}
