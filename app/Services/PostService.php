<?php

namespace App\Services;

use App\Interfaces\IPosts;
use App\Models\Album;
use App\Models\Artist;
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
        return response()->json($post->load(["song", "tags","album","artist"]));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
         $post = $this->post::create([
            "title" => $request->title,
            "desc" => $request->desc,
            "slug" => $request->slug,
            "artist" => $request->artist,
            "entity" => $request->entity,
            "img" => $request->has("img") ? FileUploader::img($request) : null,
            "lyric" => $request->lyric
        ]);

         $request->has("src") && $post->song()->create([
            "src" => FileUploader::song($request)
         ]);

         $request->has("tags") && $post->tags()->attach($request->tags);

         $request->has("album")
         &&
         Album::find($request->album)
         &&
         $post->album()->attach($request->album);

         $request->has("artist_id")
         &&
         Artist::find($request->artist_id)
         &&
         $post->artst()->attach($request->artist_id);


         return \response()->json([
             "created" => true,
             "post" => $post->load("song")
         ]);

    }

    /**
     * @param Post $post
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Post $post, Request $request): JsonResponse
    {
        $post = $post->update([
            "title" => $request->title ?? $post->title,
            "desc" => $request->desc ?? $post->desc,
            "slug" => $request->slug ?? $post->slug,
            "artist" => $request->artist ?? $post->artist,
            "img" => $request->has("img") ?
                FileUploader::img($request) : $post->img,
            "entity" => $request->entity ?? $post->entity,
            "lyric" => $request->lyric ?? $post->lyric
        ]);

        $request->has("src") && $post->song()->update([
            "src" => FileUploader::song($request) ?? $post->song->src
        ]);

        $request->has("tags") && $post->tags()->async($request->tags);

        $request->has("album") && Album::find($request->album) && $post->album()->async($request->album);

        $request->has("artist_id") && Artist::find($request->artist_id) && $post->artist()->async($request->artist);

        return \response()->json([
            "updated" => true,
            "post" => $post->load(["tags","song"])
        ],Response::HTTP_OK);
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
