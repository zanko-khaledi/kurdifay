<?php

namespace App\Services;

use App\Enums\Entities;
use App\Events\FileUploader;
use App\Events\SongUploader;
use App\Models\Post;
use App\Models\Song;
use App\Models\Subcategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PostService implements \App\Interfaces\IPosts
{

    protected Post $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * @return JsonResponse
     */
    public function getAllPosts(): JsonResponse
    {
        return response()->json($this->post::all());
    }

    /**
     * @param Post $post
     * @return JsonResponse
     */
    public function findPostById(Post $post): JsonResponse
    {
        return response()->json($post);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $subcategory = Subcategory::all()->find($request->input("subcategory_id"))->first();

        if($subcategory){
            if($request->input("entity") === Entities::SONG->getEntity()){

                $post = $this->post->create([
                    "subcategory_id" => $subcategory->id,
                    "title" => $request->input("title"),
                    "desc" => $request->input("desc"),
                    "slug" => $request->input("slug"),
                    "lyric" => $request->input("lyric"),
                    "img" => FileUploader::dispatch($request)[0],
                    "artist" => $request->input("artist"),
                    "entity" => $request->input("entity"),
                ]);

                $post && Song::create([
                    "post_id" => $post->id,
                    "src" => SongUploader::dispatch($request)[0]
                ]);

                return response()->json([
                    "post" => $this->post::with("song")->where("id",$post->id)->first(),
                    "created" => true
                ],Response::HTTP_CREATED);

            }else{
                $post = $this->post->create([
                    "subcategory_id" => $subcategory->id,
                    "title" => $request->input("title"),
                    "desc" => $request->input("desc"),
                    "slug" => $request->input("slug"),
                    "lyric" => $request->input("lyric"),
                    "img" => $request->has("img") ? FileUploader::dispatch($request)[0] : null,
                    "entity" => $request->input("entity"),
                    "artist" => $request->input("artist"),
                ]);

                return \response()->json([
                    "post" => $post,
                    "created" => true
                ],Response::HTTP_CREATED);
            }
        }else{
            return \response()->json([
                "message" => "Subcategory with id {$subcategory->id} not founded!"
            ],Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @param Post $post
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Post $post, Request $request): JsonResponse
    {
        if($request->has("src")){
            $post_updated = $post->update([
                "subcategory_id" => $request->input("subcategory_id") ?? $post->subcategory_id,
                "title" => $request->input("title") ?? $post->title,
                "desc" => $request->input("desc") ?? $post->desc,
                "slug" => $request->input("slug") ?? $post->slug,
                "artist" => $request->input("artist") ?? $post->artist,
                "entity" => $request->input("entity") ?? $post->entity,
                "lyric" => $request->input("lyric") ?? $post->lyric,
                "img" => FileUploader::dispatch($request) ?? $post->img,
            ]);

            $post_updated->song()->update([
                "src" => SongUploader::dispatch($request) ?? $post->song()->src
            ]);

            return \response()->json([
                "post" => $post_updated,
                "updated" => true
            ],Response::HTTP_OK);
        }else{
            $post_updated = $post->update([
                "subcategory_id" => $request->input("subcategory_id") ?? $post->subcategory_id,
                "title" => $request->input("title") ?? $post->title,
                "desc" => $request->input("desc") ?? $post->desc,
                "slug" => $request->input("slug") ?? $post->slug,
                "artist" => $request->input("artist") ?? $post->artist,
                "entity" => $request->input("entity") ?? $post->entity,
                "lyric" => $request->input("lyric") ?? $post->lyric,
                "img" => FileUploader::dispatch($request) ?? $post->img,
            ]);

            return \response()->json([
                "post" => $post_updated,
                "updated" => true
            ]);
        }
    }

    /**
     * @param Post $post
     * @return JsonResponse
     */
    public function delete(Post $post): JsonResponse
    {
        $post->delete();

        return \response()->json([
            "deleted" => true,
            "post" => $post
        ],Response::HTTP_OK);
    }
}
