<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Services\AlbumsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlbumsController extends Controller
{


    private AlbumsService $albumsService;


    public function __construct()
    {
        $this->albumsService = new AlbumsService(new Album());
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->albumsService->getAllAlbums();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return $this->albumsService->create($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return JsonResponse
     */
    public function show(Album $album): JsonResponse
    {
        return $this->albumsService->findAlbumsById($album);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Album  $album
     * @return JsonResponse
     */
    public function update(Request $request, Album $album): JsonResponse
    {
        return $this->albumsService->update($album,$request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Album  $album
     * @return JsonResponse
     */
    public function destroy(Album $album): JsonResponse
    {
        return  $this->albumsService->delete($album);
    }
}
