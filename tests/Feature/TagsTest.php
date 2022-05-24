<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TagsTest extends TestCase
{

    use RefreshDatabase;

    protected object $tags;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        Sanctum::actingAs(User::factory()->create());

        $this->tags = Tag::factory()->count(10)->create();
    }

    /**
     * @test
     */
    public function get_all_tags_test()
    {

        $this->withoutExceptionHandling();

        $response = $this->get(route("tags.index"))
            ->assertOk()
            ->json();
        $this->assertGreaterThan(0,count($response));
        $this->assertDatabaseHas("tags",[
            "name" => $response[0]["name"]
        ]);
    }


    /**
     * @test
     */
    public function get_single_tag_test()
    {
        $this->withoutExceptionHandling();

        $response = $this->get(route("tags.show",[
            "tag" => Tag::all()->first()->id
        ]))->assertOk()->json();

        $this->assertEquals($this->tags[0]->name,$response["name"]);
    }


    /**
     * @test
     */
    public function create_tags_test()
    {
       $this->withoutExceptionHandling();

       $this->post(route("tags.store"),[
           "tags" => [
               "zanko","milad","teddy"
           ]
       ])->assertCreated()->assertJson([
           "created" => true
       ]);

       $this->assertDatabaseHas("tags",[
           "name" => "milad"
       ]);
    }

    /**
     * @test
     */
    public function find_tags_with_querystring_test()
    {

        $this->withoutExceptionHandling();

        $response = $this->get(route("tags.index",[
            "name=M"
        ]))->assertOk()->json();

        $this->assertGreaterThan(0,count($response));
        $this->assertDatabaseHas("tags",[
            "name" => $response[0]["name"]
        ]);
    }

    /**
     * @test
     */
    public function delete_tags_test()
    {
        $this->withoutExceptionHandling();


        $tag = Tag::all()->first();

        $this->deleteJson(route("tags.destroy",[
            "tag" => $tag->id
        ]))->assertOk()->assertJson([
            "deleted" => true
        ]);

        $this->assertDatabaseMissing("tags",[
            "name" => $tag->name
        ]);
    }

}
