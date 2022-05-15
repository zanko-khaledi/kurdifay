<?php

namespace App\Http\Middleware;

use App\Enums\Rules;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MustAtLeastOneRecord
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next): \Illuminate\Http\JsonResponse
    {
        if(!$request->user()->tokenCan("admin:*") && count(User::all()) <= 1){
           return response()->json([
               "message" => "There is most at least have one record in Users table."
           ],Response::HTTP_FORBIDDEN);
        }elseif ($request->user()->tokenCan("admin:*")){
             if(count(User::all()) <= 1 && User::all()->find($request->user()->id)->rules === Rules::ADMIN->getRules()){
                 return \response()->json([
                     "message" => "There is most at least exist one Admin."
                 ],Response::HTTP_FORBIDDEN);
             }
        }
        return $next($request);
    }
}
