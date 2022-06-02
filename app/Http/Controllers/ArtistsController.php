<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Services\ArtistsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArtistsController extends Controller
{

    private ArtistsService $artistsService;

    public function __construct()
    {
        $this->artistsService = new ArtistsService(new Artist());
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->artistsService->getAllArtists();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return  $this->artistsService->create($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Artist  $artist
     * @return JsonResponse
     */
    public function show(Artist $artist): JsonResponse
    {
        return  $this->artistsService->findArtistById($artist);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function edit(Artist $artist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Artist  $artist
     * @return JsonResponse
     */
    public function update(Request $request, Artist $artist): JsonResponse
    {
        return $this->artistsService->update($artist,$request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Artist  $artist
     * @return JsonResponse
     */
    public function destroy(Artist $artist): JsonResponse
    {
        return $this->artistsService->delete($artist);
    }
}
