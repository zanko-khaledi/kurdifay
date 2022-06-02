<?php

namespace App\Services;

use App\Models\Artist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ArtistsService implements \App\Interfaces\IArtists
{

    private Artist $artist;

    public function __construct(Artist $artist)
    {
        $this->artist = $artist;
    }

    /**
     * @return JsonResponse
     */
    public function getAllArtists(): JsonResponse
    {
        return response()->json($this->artist::with("posts")->get());
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
            "img" => "nullable | mimes:jpg,png,gif | max : 10000"
        ]);


       $artist = $this->artist::create([
            "name" => $validation["name"],
            "desc" => $validation["desc"],
            "slug" => $validation["slug"],
            "img" => $request->has("img") ? FileUploader::img($request) : null
        ]);

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
            "img" => "nullable | max: 10000 | mimes:jpg,jpeg,png,gif"
        ]);

        $artist->update([
            "name" => $request->name ?? $artist->name,
            "desc" => $request->desc ?? $artist->desc,
            "slug" => $request->slug ?? $artist->slug,
            "img" => $request->has("img") ? FileUploader::img($request) : $artist->img
        ]);


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
