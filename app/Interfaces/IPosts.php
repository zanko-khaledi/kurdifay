<?php

namespace App\Interfaces;

use App\Models\Post;
use Illuminate\Http\Request;

interface IPosts
{

    public function getAllPosts();

    public function findPostById(Post $post);

    public function create(Request $request);

    public function update(Post $post,Request $request);

    public function delete(Post $post);
}
