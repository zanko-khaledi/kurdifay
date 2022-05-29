<?php

namespace App\Services;

use App\Interfaces\IPosts;
use App\Models\Post;
use App\Models\Subcategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class PostService implements IPosts
{

    protected Post $post;

    protected const  PAGE_SIZE = 15;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * @return JsonResponse
     */
    public function getAllPosts(): JsonResponse
    {
        $post = $this->post::with(["song", "tags"])->orderBy("created_at", request()->get("sort") ?? "asc")
            ->paginate(self::PAGE_SIZE);


        if (\request()->has(["title", "artist"])) {
            $with_titles = $this->post::with(["song", "tags"])->where("title", "LIKE", "%" . \request()->get("title") . "%")
                ->orderBy("created_at", \request()->get("sort") ?? "asc")
                ->get();

            $with_aritsts = $this->post::with(["song", "tags"])->where("artist", "LIKE", "%" . \request()->get("artist") . "%")
                ->orderBy("created_at", \request()->get("sort") ?? "asc")
                ->get();

            $post = [...$with_aritsts, ...$with_titles];

        } else {
            if (request()->has("title")) {
                $title = request()->get("title");
                $post = $this->post::with(["song", "tags"])->where("title", "LIKE", "%" . $title . "%")
                    ->orderBy("created_at", \request()->has("sort") ? \request()->get("sort") : "asc")
                    ->get();
            }

            if (request()->has("artist")) {
                $artist = request()->get("artist");
                $post = $this->post::with(["song", "tags"])->where("artist", "LIKE", "%" . $artist . "%")
                    ->orderBy("created_at", \request()->has("sort") ? \request()->get("sort") : "asc")
                    ->get();
            }

            if (request()->has("slug")) {
                $slug = request()->get("slug");
                $post = $this->post::with(["song", "tags"])->where("slug", "LIKE", "%" . $slug . "%")
                    ->get();
            }
        }

        return \response()->json($post);
    }

    /**
     * @param Post $post
     * @return JsonResponse
     */
    public function findPostById(Post $post): JsonResponse
    {
        return response()->json($post->load(["song", "tags"]));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $subcategory = Subcategory::find($request->input("subcategory_id"));

        if ($subcategory) {
            $post = $this->post::create([
                "subcategory_id" => $subcategory->id,
                "title" => $request->title,
                "desc" => $request->desc,
                "artist" => $request->artist,
                "slug" => $request->slug,
                "lyric" => $request->lyric,
                "entity" => $request->entity,
                "img" => $request->has("img") ? FileUploader::img($request) : null
            ]);

            $request->has("tags") && $post->tags()->createMany(array_map(fn($tag) => [
                "name" => $tag
            ], $request->tags));

            $request->has("src") && $post->song()->create([
                "src" => FileUploader::song($request)
            ]);


            return $post ?
                \response()->json([
                    "post" => $post->load(["song", "tags"]),
                    "created" => true
                ], Response::HTTP_CREATED)
                :
                \response()->json([
                    "created" => false
                ], Response::HTTP_BAD_REQUEST);
        } else {
            return \response()->json([
                "message" => "Subcategory not found!",
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @param Post $post
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Post $post, Request $request): JsonResponse
    {


        $post->update([
            "title" => $request->title ?? $post->title,
            "desc" => $request->desc ?? $post->desc,
            "slug" => $request->slug ?? $post->slug,
            "artist" => $request->artist ?? $post->artist,
            "entity" => $request->entity ?? $post->entity,
            "img" => $request->has("img") ? FileUploader::img($request) : $post->img
        ]);

        $post->song()->update([
            "src" => $request->has("src") ? FileUploader::song($request) : $post->song->src
        ]);

        if ($request->has("tags") && is_array($request->tags)) {
            $post->tags()->sync($request->tags);
        }

        return \response()->json([
            "post" => $post->load(["song", "tags"]),
            "updated" => true
        ], Response::HTTP_OK);

    }

    /**
     * @param Post $post
     * @return JsonResponse
     */
    public function delete(Post $post): JsonResponse
    {

        $img_name = public_path("/files/" . last(explode("/", $post->img)));
        File::exists($img_name) && File::delete($img_name);

        if ($post->load("song")->src !== null) {
            $song_name = public_path("/songs/" . last(explode("/", $post->song->src)));
            File::exists($song_name) && File::delete($song_name);
        }

        $post->delete();

        return \response()->json([
            "deleted" => true
        ]);
    }
}
