<?php

namespace App\Services;

use App\Enums\Entities;
use App\Events\FileUploader;
use App\Events\SongUploader;
use App\Interfaces\IPosts;
use App\Models\Post;
use App\Models\Song;
use App\Models\Subcategory;
use App\Models\Tag;
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
        $post = $this->post::with("song")->orderBy("created_at",request()->get("sort") ?? "asc")
            ->paginate(self::PAGE_SIZE);


        if(\request()->has(["title","artist"])){
            $with_titles = $this->post::with("song")->where("title","LIKE","%".\request()->get("title")."%")
                ->orderBy("created_at",\request()->get("sort") ?? "asc")
                ->get();

            $with_aritsts = $this->post::with("song")->where("artist","LIKE","%".\request()->get("artist")."%")
                ->orderBy("created_at",\request()->get("sort") ?? "asc")
                ->get();

            $post =[...$with_aritsts,...$with_titles];

        }else{
            if(request()->has("title")){
                $title = request()->get("title");
                $post = $this->post::with("song")->where("title","LIKE","%".$title."%")
                ->orderBy("created_at",\request()->has("sort") ? \request()->get("sort") : "asc")
                ->get();
            }

            if (request()->has("artist")){
                $artist = request()->get("artist");
                $post = $this->post::with("song")->where( "artist","LIKE","%".$artist."%")
                    ->orderBy("created_at",\request()->has("sort") ? \request()->get("sort") : "asc")
                    ->get();
            }

            if(request()->has("slug")){
                $slug = request()->get("slug");
                $post = $this->post::with("song")->where("slug","LIKE","%".$slug."%")
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
        return response()->json($post->load("song"));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $subcategory = Subcategory::find($request->input("subcategory_id"));

        if($subcategory){

            $post  = Post::create([
                "subcategory_id" => $subcategory->id,
                "title" => $request->input("title"),
                "desc" => $request->input("desc"),
                "slug" => $request->input("slug"),
                "entity" => $request->input('entity'),
                "artist" => $request->input('artist'),
                "lyric" => $request->input("lyric"),
                "img" => $request->has("img") ?  FileUploader::dispatch($request)[0] : null,
            ]);

            $post && $request->input("entity") === Entities::SONG->getEntity() && $post->song()->create([
                "src" => SongUploader::dispatch($request)[0]
            ]);

            if($request->has("tags")){
                $tags = array_map(fn($tag)=> [
                    "name" => $tag
                ],$request->input("tags"));

                $post && $request->has("tags") && $post->tags()->createMany($tags);
            }

            return \response()->json([
                "post" => $post->load("song"),
                "created" => true
            ],Response::HTTP_CREATED);

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
        $post->subcategory_id = $request->subcategory_id ?? $post->subcategory_id;
        $post->title = $request->title ?? $post->title;
        $post->desc = $request->desc ?? $post->desc;
        $post->artist = $request->artist ?? $post->artist;
        $post->slug = $request->slug ?? $post->slug;
        $post->lyric = $request->lyric ?? $post->lyric;
        $post->img = $request->has("img") ?
            FileUploader::dispatch($request)[0]
            &&
            File::exists( public_path("/files/".last(explode("/",$post->img))))
            &&
            File::delete( public_path("/files/".last(explode("/",$post->img))))
            : $post->img;
        $request->has("src") ? $post->song()->update([
            "src" => $request->has("src") ?
                SongUploader::dispatch($request)[0]
                &&
                File::exists(public_path("/songs/".last(explode("/",$post->song->src))))
                &&
                File::delete(public_path("/songs/".last(explode("/",$post->song->src))))
                : $post->song->src
        ]) : '';

        $request->has("tags_id")
        &&
        is_array($request->input("tags_id"))
        &&
        $post->tags()->sync($request->input("tags_id"));

        $post->save();

        return \response()->json([
            "post" => $post,
            "updated" => true
        ]);
    }

    /**
     * @param Post $post
     * @return JsonResponse
     */
    public function delete(Post $post): JsonResponse
    {

        $img_name = public_path("/files/".last(explode("/",$post->img)));
        File::exists($img_name) && File::delete($img_name);

        if($post->load("song")->src !== null){
            $song_name = public_path("/songs/".last(explode("/",$post->song->src)));
            File::exists($song_name) && File::delete($song_name);
        }

        $post->delete();

        return \response()->json([
            "deleted" => true
        ]);
    }
}
