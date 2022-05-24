<?php

namespace App\Interfaces;

use App\Models\Comment;
use Illuminate\Http\Request;


interface IComment
{

    public function findAllComments();

    public function findCommentById(Comment $comment);

    public function create(Request $request);

    public function update(Comment $comment,Request $request);

    public function delete(Comment $comment);
}
