<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{

    protected UserService $userServices;

    public function  __construct()
    {
        $this->userServices = new UserService(new User());
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return  $this->userServices->findAll();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return $this->userServices->create($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        $this->userServices = new UserService($user);

        return  $this->userServices->findOne();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return Response
     */
    public function edit(User $user)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Models\User  $user
     * @return JsonResponse
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $this->userServices = new UserService($user);

        return $this->userServices->update($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $this->userServices = new UserService($user);

        return  $this->userServices->remove();
    }
}
