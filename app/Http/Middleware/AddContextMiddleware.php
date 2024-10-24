<?php 

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AddContextMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // before response
        $trace_id = $request->header('X-Trace-Id') ?? Str::uuid()->toString();
        $request->request->add(['trace_id' => $trace_id]);
        
        // global log add context
        Log::withContext([
            'endpoint' => $request->path(),
            'method' => $request->method(),
            // 'payload' => $request->all(),
            'trace_id' => $request->trace_id,
        ]);

        // after response
        $response = $next($request);
        $response->headers->set('X-Trace-Id', $trace_id);

        return $response;
    }
}