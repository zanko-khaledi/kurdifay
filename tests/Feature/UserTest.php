<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public  function  create_user_test_example()
    {
        $user =$this->postJson(route("users.create"),[
            "name" => "zanko",
            "email" => "zanko@gmail.com",
            "password" => "123456"
        ])->assertCreated()->json();

        $this->assertEquals($user["name"],User::first()->name);
        $this->assertDatabaseHas("users",["name" => $user["name"]]);
    }
}
