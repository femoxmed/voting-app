<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ShareholderMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role !== 'shareholder') {
            abort(403, 'Access denied. Shareholders only.');
        }

        // Ensure user has shareholder record
        if (!auth()->user()->shareholders()->exists()) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not registered as a shareholder.');
        }

        return $next($request);
    }
}
