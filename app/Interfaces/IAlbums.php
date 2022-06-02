<?php

namespace App\Interfaces;

use App\Models\Album;
use Illuminate\Http\Request;

interface IAlbums
{

    public function getAllAlbums();

    public function findAlbumsById(Album $album);

    public function create(Request $request);

    public function update(Album $album,Request $request);

    public function delete(Album $album);
}
