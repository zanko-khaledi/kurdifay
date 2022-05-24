<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Services\TagsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class TagsController extends Controller
{


    protected TagsService $tagsService;


    public function __construct()
    {
        $this->tagsService = new TagsService(new Tag());
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     */
    public function index(): JsonResponse
    {
       return $this->tagsService->getAllTags();
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
        return $this->tagsService->create($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return JsonResponse
     */
    public function show(Tag $tag): JsonResponse
    {
        return $this->tagsService->getTagById($tag);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return JsonResponse
     */
    public function update(Request $request, Tag $tag): JsonResponse
    {
        return  $this->tagsService->update($tag,$request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return JsonResponse
     */
    public function destroy(Tag $tag): JsonResponse
    {
        return $this->tagsService->delete($tag);
    }
}
