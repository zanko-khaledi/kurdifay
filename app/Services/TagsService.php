<?php

namespace App\Services;

use App\Interfaces\ITag;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class TagsService implements ITag
{

    protected Tag $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    /**
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     */
    public function getAllTags(): JsonResponse
    {

        try {
            if (\request()->get("name")) {
                $tags = Tag::where("name", "LIKE", "%" . \request()->get("name") . "%")->get();
            } else {
                $tags = Tag::all();
            }
        } catch (NotFoundExceptionInterface $e) {
           $tags = $e->getMessage();
        }

        return response()->json($tags);
    }

    /**
     * @param Tag $tag
     * @return JsonResponse
     */
    public function getTagById(Tag $tag): JsonResponse
    {
        return response()->json($tag);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create( Request $request): JsonResponse
    {
        $validation = Validator::make($request->all(),[
            "tags" => "nullable | array"
        ]);

        if(!$validation->fails()){

            try {
                $tags = array_map(fn($tag)=> [
                    "name" => $tag
                ],(array)$request->input("tags"));

                $tags_created = $this->tag::insert($tags);

                return response()->json([
                    "created" => $tags_created
                ],Response::HTTP_CREATED);

            }catch (\PDOException $e){
                return \response()->json([
                    "message" => $e->getMessage()
                ],Response::HTTP_FORBIDDEN);
            }
        }else{
            return \response()->json([
                "message" => $validation->getMessageBag()
            ],Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param Tag $tag
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Tag $tag, Request $request): JsonResponse
    {
        $tag->update([
            "name" => $request->name
        ]);

        return \response()->json([
            "updated" => true
        ],Response::HTTP_OK);
    }

    /**
     * @param Tag $tag
     * @return JsonResponse
     */
    public function delete(Tag $tag): JsonResponse
    {
        $tag->delete();

        return \response()->json([
            "deleted" => true
        ],Response::HTTP_OK);
    }
}
