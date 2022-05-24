<?php

namespace App\Services;

use App\Enums\CommentType;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentService implements \App\Interfaces\IComment
{

    protected Comment $comment;


    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return JsonResponse
     */
    public function findAllComments(): JsonResponse
    {
        return response()->json($this->comment::with("replies")->get());
    }

    /**
     * @param Comment $comment
     * @return JsonResponse
     */
    public function findCommentById(Comment $comment): JsonResponse
    {
        return response()->json($comment->load("replies"));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        if($request->has("entity") && $request->entity === CommentType::REPLAY->getType())
        {
            $comment = $this->comment::find($request->comment_id);

            if($comment){

                $created = $comment->replies()->create([
                    "name" => $request->name,
                    "email" => $request->email,
                    "comment" => $request->comment
                ]);

            }else{

                return response()->json([
                    "message" => "Comment with id ".$comment->id." not founded!"
                ],Response::HTTP_NOT_FOUND);

            }
        }else{

            $created = $this->comment::create([
                "name" => $request->name,
                "email" => $request->email,
                "comment" => $request->comment
            ]);

        }

        return response()->json([
            "comment" => $this->comment,
            "created" => $created
        ]);
    }

    /**
     * @param Comment $comment
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Comment $comment, Request $request): JsonResponse
    {
        if($request->has("entity") && $request->entity === CommentType::REPLAY->getType()){

            $updated = $comment->replies()->update([
                "status" => $request->status ?? $comment->replies->status
            ]);

        }else{

            $updated = $comment->update([
                "status" => $request->status ?? $comment->status
            ]);

        }

        return \response()->json([
            "updated" => $updated
        ],Response::HTTP_OK);
    }

    /**
     * @param Comment $comment
     * @return JsonResponse
     */
    public function delete(Comment $comment): JsonResponse
    {

        $comment->delete();

        return \response()->json([
            "deleted" => true
        ]);
    }
}
