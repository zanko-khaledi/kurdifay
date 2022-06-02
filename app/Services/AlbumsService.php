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

    public function __construct(Album $album)
    {
        $this->album = $album;
    }

    /**
     * @return JsonResponse
     */
    public function getAllAlbums(): JsonResponse
    {
        return response()->json($this->album::with("posts")->get());
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
        $validation = Validator::make($request->all(),[
            "name" => "required",
            "img" => "nullable | mimes:jpg,jpeg,png,gif",
            "slug" => "required",
            "desc" => "nullable | string"
        ]);

        if(!$validation->fails()){

            $album = $this->album::create([
                "name" => $request->name,
                "img" =>  FileUploader::img($request),
                "slug" => $request->slug,
                "desc" => $request->desc
            ]);

            return response()->json([
                "created" => true,
                "album" => $album
            ],Response::HTTP_CREATED);

        }else{
            return \response()->json([
                "message" => $validation->getMessageBag()
            ],Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param Album $album
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Album $album, Request $request): JsonResponse
    {
        $album->update([
            "name" => $request->name ?? $album->name,
            "img" => $request->has("img") ?
                FileUploader::img($request) : $album->img,
            "slug" => $request->slug ?? $album->slug,
            "desc" => $request->desc ?? $album->desc
        ]);

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
