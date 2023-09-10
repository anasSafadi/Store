<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Only_post_request
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->method("POST")=="POST"){
            return $next($request);
        }else{
            return response()->json(["status"=>false,"status_code"=>400,"message"=>"("."POST".")"." "."هذا المسار من نوع "]);
        }
    }
}
