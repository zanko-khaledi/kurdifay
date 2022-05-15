<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Http\Request;

interface IUsers
{


    public function findAllUsers();

    public function findUsersById(User $user);

    public function create(Request $request);

    public function update(User $user,Request $request);

    public function delete(User $user);
}
