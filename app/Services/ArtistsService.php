<?php

namespace App\Services;

use App\Models\Artist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ArtistsService implements \App\Interfaces\IArtists
{

    private Artist $artist;
    private  const PAGE_SIZE = 15;

    public function __construct(Artist $artist)
    {
        $this->artist = $artist;
    }

    /**
     * @return JsonResponse
     */
    public function getAllArtists(): JsonResponse
    {
        if(request()->has("artist")){
            $query_string = request()->get("artist");
            $record = $this->artist::with("posts")->where("artist","LIKE","%".$query_string."%");
        }else{
            $record = $this->artist::with("posts")->orderBy("created_at","DESC")
              ->paginate(static::PAGE_SIZE);
        }

        return response()->json($record);
    }

    /**
     * @param Artist $artist
     * @return JsonResponse
     */
    public function findArtistById(Artist $artist): JsonResponse
    {
        return response()->json($artist->load("posts"));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $validation = $request->validate([
            "name" => "required",
            "desc" => "nullable | string",
            "slug" => "required",
            "img" => "nullable | mimes:jpg,png,gif | max : 10000",
            "tags" => "nullable | array"
        ]);


       $artist = $this->artist::create([
            "name" => $validation["name"],
            "desc" => $validation["desc"],
            "slug" => $validation["slug"],
            "img" => $request->has("img") ? FileUploader::img($request) : null
        ]);

       $request->has("tags") && $artist->tags()->attach($request->tags);
    

        return response()->json([
            "created" => true,
            "artist"  => $artist
        ], Response::HTTP_CREATED);
    }

    /**
     * @param Artist $artist
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Artist $artist, Request $request): JsonResponse
    {

        $request->validate([
            "img" => "nullable | max: 10000 | mimes:jpg,jpeg,png,gif",
            "tags" => "nullable | array"
        ]);

        $artist->update([
            "name" => $request->name ?? $artist->name,
            "desc" => $request->desc ?? $artist->desc,
            "slug" => $request->slug ?? $artist->slug,
            "img" => $request->has("img") ? FileUploader::img($request) : $artist->img
        ]);

        $request->has("tags") && $artist->tags()->sync($request->tags);


        return \response()->json([
            "updated" => true,
            "artist" => $artist
        ],Response::HTTP_OK);

    }

    /**
     * @param Artist $artist
     * @return JsonResponse
     */
    public function delete(Artist $artist): JsonResponse
    {
        $artist->delete();

        return \response()->json([
            "deleted" => true,
            "artist" => $artist
        ],Response::HTTP_OK);
    }
}
