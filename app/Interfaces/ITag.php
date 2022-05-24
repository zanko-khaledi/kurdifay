<?php

namespace App\Interfaces;

use App\Models\Tag;
use Illuminate\Http\Request;

interface ITag
{


    public function getAllTags();

    public function getTagById(Tag $tag);

    public function create(Request $request);

    public function update(Tag $tag,Request $request);

    public function delete(Tag $tag);
}
