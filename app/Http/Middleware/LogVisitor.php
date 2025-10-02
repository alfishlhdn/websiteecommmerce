<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Visitor_Log;
use Illuminate\Http\Request;

class LogVisitor
{
    public function handle($request, Closure $next)
    {
        $ip = $request->ip();
        $userAgent = $request->header('User-Agent');
        $today = Carbon::today();

        $alreadyLogged = Visitor_Log::where('ip_address', $ip)
            ->where('user_agent', $userAgent)
            ->whereDate('created_at', $today)
            ->exists();

        if (! $alreadyLogged) {
            Visitor_Log::create([
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'url' => $request->fullUrl(),
            ]);
        }

        return $next($request);
    }

}
