<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OnlyCompanyMiddleware 
{
    public function handle(Request $request, Closure $next)
    {
        // bypass if user is_admin
        if ($request->user()->is_admin === true) {
            return $next($request);
        }

        // allowed if user_type = company
        if ($request->user()->user_type === 'company') {
            return $next($request);
        }

        return response()->json(['message' => 'Permission denied'], 403);
    }
}