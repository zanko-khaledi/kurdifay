<?php

namespace App\services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserService
{

    /**
     * @var User
     */
    protected User $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }


    /**
     * @return JsonResponse
     */
    public function findAll(): JsonResponse
    {
        return response()->json($this->user::all());
    }

    /**
     * @return JsonResponse
     */
    public function findOne(): JsonResponse
    {
        return \response()->json($this->user);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request):JsonResponse
    {
        $this->user->name = $request->input("name");
        $this->user->email = $request->input("email");
        $this->user->password = Hash::make($request->input("password"));
        $this->user->save();

        return response()->json($this->user,Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $updated = $this->user->update([
            "name" => $request->input("name") ?? $this->user->name,
            "email" => $request->input("email") ?? $this->user->email,
            "password" => Hash::make($request->input("password")) ?? $this->user->password
        ]);

        return \response()->json([
            "user" => $updated["name"],
            "updated" => true
        ],Response::HTTP_OK);
    }

    /**
     * @return JsonResponse
     */
    public function remove(): JsonResponse
    {
         $this->user->delete();

         return \response()->json([
             "user" => $this->user->name,
             "deleted" => true
         ],Response::HTTP_OK);
    }
}
