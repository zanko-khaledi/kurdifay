<?php

namespace Tests\Feature;

use App\Enums\Rules;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public  function  create_user_test_example()
    {

        Sanctum::actingAs(User::factory()->create(),["admin:*"]);

        $user =$this->postJson(route("users.create"),[
            "name" => "Zanko",
            "email" => "zanko@gmail.com",
            "rules" => Rules::ADMIN->getRules(),
            "password" => Hash::make("1234567")
        ])->assertCreated()->json();


        $this->assertEquals($user["name"],User::all()->where("name",$user["name"])->first()->name);
        $this->assertDatabaseHas("users",["name" => $user["name"]]);
    }


    /**
     * @test
     */
    public function get_users_list_test()
    {
        Sanctum::actingAs(User::factory()->create(),["admin:*"]);

        $list = $this->getJson(route("users"))
            ->assertOk()->json();

        $this->assertGreaterThan(0,count($list));
    }


    /**
     * @test
     */
    public function update_user_test()
    {
        $user = Sanctum::actingAs(User::factory()->create(),["admin:*"]);

       $this->patchJson(route("users.update",["user" => $user["id"]]),[
            "name" => "Zanko",
            "email" => "zanko@gmail.com"
        ])
            ->assertOk()->json();

        $this->assertEquals("Zanko",User::all()->first()->name);
        $this->assertEquals("zanko@gmail.com",User::all()->first()->email);
    }

    /**
     * @test
     */
    public function get_single_user_test()
    {
        $user = Sanctum::actingAs(User::factory()->create(),["admin:*"]);

        $single_user = $this->getJson(route("users.show",["user" => $user["id"]]))
            ->assertOk()->json();

        $this->assertGreaterThan(0,count($single_user));
    }

    /**
     * @test
     */
    public function delete_user_test()
    {
        $user = Sanctum::actingAs(User::factory()->create(),['admin:*']);

        User::factory()->create();

        $this->deleteJson(route("users.delete",["user" => $user["id"]]))
            ->assertOk()->assertJson([
                "deleted" => true
            ]);
    }

    /**
     * @test
     */
    public function delete_user_forbidden()
    {
        $user = Sanctum::actingAs(User::factory()->create(),["admin:*"]);

        $this->deleteJson(route("users.delete",["user" => $user["id"]]))
            ->assertForbidden()->json();
    }


}
