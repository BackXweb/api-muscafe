<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LastOneAt
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->guest()) {
            return $next($request);
        }

        if (empty($request->user()->last_online_at) || $request->user()->last_online_at->diffInMinutes(now()) > 5) {
            DB::table("users")
                ->where("id", $request->user()->id)
                ->update(["last_online_at" => now()]);
        }

        return $next($request);
    }
}
