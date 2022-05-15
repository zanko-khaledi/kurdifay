<?php

namespace App\Services;

use App\Interfaces\IUsers;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserService implements IUsers
{

    protected User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * @return JsonResponse
     */
    public function findAllUsers(): JsonResponse
    {
        return response()->json($this->user::all());
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function findUsersById(User $user): JsonResponse
    {
        return response()->json($user);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {

        $required = [
            "name" => $request->input("name"),
            "email" => $request->input("email"),
            "password" => Hash::make($request->input("password")),
            "rules" => $request->input("rules")
        ];

        $user = $this->user::create($required);

        return response()->json($user,Response::HTTP_CREATED);
    }

    /**
     * @param User $user
     * @param Request $request
     * @return JsonResponse
     */
    public function update(User $user, Request $request): JsonResponse
    {
        $user = $user->update([
            "name" => $request->input("name") ?? $user->name,
            "email" => $request->input("email") ?? $user->email,
            "password" => Hash::make($request->input("password")) ?? $user->password,
            "rules" => $request->input("rules") ?? $user->rules
        ]);

        return \response()->json([
            "user" => $user,
            "update" => true
        ],Response::HTTP_OK);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function delete(User $user): JsonResponse
    {
        $user->delete();

        return \response()->json([
            "user" => $user,
            "deleted" => true
        ],Response::HTTP_OK);
    }
}
