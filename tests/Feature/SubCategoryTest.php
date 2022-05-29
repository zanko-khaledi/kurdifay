<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Testing\File;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SubCategoryTest extends TestCase
{

    use RefreshDatabase;


    /**
     * @test
     */

    public function get_subcategories_list_test()
    {
        Sanctum::actingAs(User::factory()->create());


        $category = Category::factory()->create();

         Subcategory::factory()->create([
            "category_id" => $category->id,
        ]);

        $this->getJson(route("sub_categories"))
            ->assertOk()->json();

        $this->assertGreaterThan(0,count(Subcategory::all()));
    }


    /**
     * @test
     */
    public function create_subcategory_test()
    {
        Sanctum::actingAs(User::factory()->create());

        $category = Category::factory()->create();

        $response = $this->postJson(route("sub_categories.create"),[
            "category_id" => $category->id,
            "title" => "zanko",
            "description" => "asdassd",
            "slug" => "asdasd",
            "img" => File::fake()->create("subcategory.jpg")
        ])->assertCreated()->json();

        $this->assertDatabaseHas("subcategories",[
            "title" => $response["title"]
        ]);
    }

    /**
     * @test
     */
    public function get_single_subcategory_test()
    {
        Sanctum::actingAs(User::factory()->create());

        $category = Category::factory()->create();

        $subcategory = Subcategory::factory()->create([
            "category_id" => $category->id,
        ]);

        $response = $this->getJson(route("sub_categories.show",["subcategory" => $subcategory->id]))
            ->assertOk()->json();

        $this->assertEquals($response["title"],Subcategory::all()->first()->title);
    }


    /**
     * @test
     */
    public function update_subcategory_test()
    {
        Sanctum::actingAs(User::factory()->create());

        $category = Category::factory()->create();

        $subcategory = Subcategory::factory()->create([
            "category_id" => $category->id,
        ]);

        $response = $this->patchJson(route("sub_categories.update",["subcategory" => $subcategory->id]),[
            "title" => "Teddy",
            "description" => "Hello Teddy"
        ])->assertOk()->assertJson([
            "updated" => true
        ]);

        $this->assertDatabaseHas("subcategories",[
            "title" => $response["subcategory"]["title"],
            "description" => $response["subcategory"]["description"]
        ]);
    }

    /**
     * @test
     */
     public function delete_subcategory_test()
     {
         Sanctum::actingAs(User::factory()->create());

         $category = Category::factory()->create();

         $subcategory = Subcategory::factory()->create([
             "category_id" => $category->id,
         ]);

         $response = $this->deleteJson(route("sub_categories.delete",["subcategory"=>$subcategory->id]))
             ->assertOk()->assertJson([
                 "deleted" => true
             ]);

         $this->assertDatabaseMissing("subcategories",[
             "title" => $response["subcategory"]["title"]
         ]);
     }
}
