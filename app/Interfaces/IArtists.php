<?php

namespace App\Interfaces;

use App\Models\Artist;
use Illuminate\Http\Request;

interface IArtists
{

    public function getAllArtists();

    public function findArtistById(Artist $artist);

    public function create(Request $request);

    public function update(Artist $artist,Request $request);

    public function delete(Artist $artist);
}
