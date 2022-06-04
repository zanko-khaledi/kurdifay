<?php

namespace App\Services;

use App\Interfaces\IAlbums;
use App\Models\Album;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AlbumsService implements IAlbums
{

    private Album $album;

    private  const PAGE_SIZE = 15;

    public function __construct(Album $album)
    {
        $this->album = $album;
    }

    /**
     * @return JsonResponse
     */
    public function getAllAlbums(): JsonResponse
    {

        if (request()->has("title")) {
            $query_string = request()->get("title");
            $record = $this->album::with("posts")->where("title", "LIKE", "%" . $query_string . "%");
        } else {
            $record = $this->album::with("posts")->orderBy("created_at", "DESC")
                ->paginate(static::PAGE_SIZE);
        }

        return response()->json($record);
    }

    /**
     * @param Album $album
     * @return JsonResponse
     */
    public function findAlbumsById(Album $album): JsonResponse
    {
        return response()->json($album->load("posts"));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $request->validate([
            "name" => "required",
            "img" => "nullable | mimes:jpg,jpeg,png,gif | max:10000",
            "slug" => "required",
            "desc" => "nullable | string",
            "tags" => "nullable | array"
        ]);

        $album = $this->album::create([
            "name" => $request->name,
            "img" =>  FileUploader::img($request),
            "slug" => $request->slug,
            "desc" => $request->desc
        ]);

        $request->has("tags") && $album->tags()->attach($request->tags);

        return response()->json([
            "created" => true,
            "album" => $album
        ], Response::HTTP_CREATED);
    }

    /**
     * @param Album $album
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Album $album, Request $request): JsonResponse
    {

        $request->validate([
            "img" => "file | mimes:png,jpg,jpeg,gif | max:10000",
            "tags" => "nullable | array"
        ]);

        $album->update([
            "name" => $request->name ?? $album->name,
            "img" => $request->has("img") ?
                FileUploader::img($request) : $album->img,
            "slug" => $request->slug ?? $album->slug,
            "desc" => $request->desc ?? $album->desc
        ]);

        $request->has("tags") && $album->tags()->sync($request->tags);

        return \response()->json([
            "updated" => true,
            "album" => $album
        ]);
    }

    /**
     * @param Album $album
     * @return JsonResponse
     */
    public function delete(Album $album): JsonResponse
    {
        $album->delete();

        return \response()->json([
            "deleted" => true,
            "album" => $album
        ]);
    }
}
