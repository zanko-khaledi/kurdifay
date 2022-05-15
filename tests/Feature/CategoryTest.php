<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryTest extends TestCase
{

    use RefreshDatabase;



    /**
     * @test
     */
    public function get_all_categories_test()
    {
        Sanctum::actingAs(User::factory()->create());

        Category::factory()->create();

        $this->getJson(route("categories"))
            ->assertOk()->json();

        $this->assertGreaterThan(0,count(Category::all()));
    }

    /**
     * @test
     */
    public function create_category_test()
    {
        Sanctum::actingAs(User::factory()->create());

        $category = $this->postJson(route("categories.create"),Category::factory()->create()->toArray())
            ->assertCreated()->json();

        $this->assertEquals($category['title'],Category::all()->first()->title);
    }


    /**
     * @test
     */

    public function get_single_category_test()
    {
        Sanctum::actingAs(User::factory()->create());

        $category = Category::factory()->create();

        $single_category = $this->getJson(route("categories.show",["category" => $category["id"]]))
            ->assertOk()->json();

       $this->assertEquals($category->title,$single_category["title"]);
    }


    /**
     * @test
     */

    public function update_category_test()
    {
        Sanctum::actingAs(User::factory()->create());

        $category = Category::factory()->create();

        $updated = $this->patchJson(route("categories.update",["category" => $category["id"]]),[
            "title" => "Teddy",
            "description" => "Hello World"
        ])->assertOk()->json();

        $this->assertDatabaseHas("categories",[
            "title" => $updated["title"],
            "description" => $updated["description"],
            "slug" => $category["slug"]
        ]);
    }


    /**
     * @test
     */
    public function delete_category_test()
    {
        Sanctum::actingAs(User::factory()->create());

        $category = Category::factory()->create();

        $this->deleteJson(route("categories.delete",["category" => $category["id"]]))
            ->assertOk()->assertJson([
                "deleted" => true
            ]);

        $this->assertDatabaseMissing("categories",[
            "title" => $category->title
        ]);
    }
}
