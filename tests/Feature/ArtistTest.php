<?php

namespace Tests\Feature;

use App\Models\Artist;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ArtistTest extends TestCase
{


    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        Sanctum::actingAs(User::factory()->create());

        Artist::factory()->count(10)->create();

        Tag::factory()->count(40)->create();
    }


    /**
     * @test
     */
    public function create_artist_test()
    {

        $this->withoutExceptionHandling();

        $response = $this->post(route("artists.store"),[
            "name" => "zanko",
            "desc" => Str::random(),
            "slug" => "zanko",
            "img" => File::fake()->create("avatar.jpg")->size(50),
            "tags" => [4,19,32] 
        ])->assertCreated()->assertJson([
            "created" => true
        ])->json();

        $this->assertDatabaseHas("artists",[
            "name" => $response["artist"]["name"]
        ]);
    }

    /**
     * @test
     */
    public function get_artists_test()
    {

        $this->get(route("artists.index"))
            ->assertOk()->json();

        $this->assertDatabaseCount("artists",Artist::all()->count());

        $this->assertGreaterThan(0,count(Artist::all()));
    }

    /**
     * @test
     */
    public function get_single_artist_test()
    {

        $artist_id =  (int)(Artist::all()->last()->id - 2);

        $response = $this->get(route("artists.show",[
            "artist" => $artist_id
        ]))->assertOk()->json();

        $this->assertEquals(Artist::find($artist_id)->name,$response["name"]);

    }

    /**
     * @test
     */
    public function update_artist_test()
    {

        $this->withoutExceptionHandling();

        Artist::factory()->count(8)->create();

        $response = $this->patch(route("artists.update",[
            "artist" => (int)(Artist::all()->last()->id - 4)
        ]),[
            "name" => "Teddy",
            "img" => File::fake()->create("avatar.jpg")->size(40),
            "tags" => [14,17]
        ])->assertOk()->assertJson([
            "updated" => true
        ]);

        $this->assertDatabaseHas("artists",[
            "name" => $response["artist"]["name"],
            "img" => $response["artist"]["img"]
        ]);
    }

    /**
     * @test
     */
    public function delete_artist_test()
    {
        $response = $this->delete(route("artists.destroy",[
            "artist" => Artist::all()->last()->id
        ]))->assertOk()->assertJson([
            "deleted" => true
        ])->json();

        $this->assertDatabaseMissing("artists",[
            "name" => $response["artist"]["name"]
        ]);

        $this->assertDatabaseCount("artists",Artist::all()->count());
    }

}
