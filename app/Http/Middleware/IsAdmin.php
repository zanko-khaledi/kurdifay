<?php

namespace App\Http\Middleware;

use App\Enums\Rules;
use Closure;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        if(!$request->user()->tokenCan("admin:*") && $request->user()->rules !== Rules::ADMIN->getRules()){
            return \response()->json([
                "message" => "Access denied! this action required Admin privilege."
            ],\Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}
