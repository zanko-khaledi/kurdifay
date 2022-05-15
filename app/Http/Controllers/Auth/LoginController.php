<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Rules;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Testing\Fluent\Concerns\Has;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {

        $validation = Validator::make($request->all(),[
            "email" => "required",
            "password" => "required"
        ]);

        if(!$validation->fails()){
            $user = User::all()->where("email",$request->input("email"))->first();

            if($user && Hash::check($request->input("password"),$user->password)){

                if($user->rules === Rules::ADMIN->getRules()){
                    $token = $user->createToken($user->name,["admin:*"])->plainTextToken;
                }elseif($user->rules === Rules::USER->getRules()){
                    $token = $user->createToken($user->name,["user:*"])->plainTextToken;
                }

                return response()->json([
                    "token" => $token
                ], 301);
            }else{
                return \response()->json([
                    "message" => "Unauthorised"
                ],401);
            }
        }else{
            return \response()->json([
                "error" => $validation->getMessageBag()
            ],400);
        }
    }
}
