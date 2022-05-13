<?php

namespace App\services;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryService
{

    /**
     * @var Category
     */
    protected Category $category;

    /**
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }


    /**
     * @return JsonResponse
     */
    public function findAll(): JsonResponse
    {
        return response()->json($this->category::all(), Response::HTTP_OK);
    }

    /**
     * @return JsonResponse
     */
    public function findOne(): JsonResponse
    {
        return \response()->json($this->category,Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $this->category->create([
            "title" => $request->input("name"),
            "description" => $request->input("description"),
            "slug" => $request->input("slug")
        ]);

        return \response()->json($this->category,Response::HTTP_CREATED);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $this->category->update([
            "title" => $request->input("title") ?? $this->category->title,
            "description" => $request->input("description") ?? $this->category->description,
            "slug" => $request->input("slug") ?? $this->category->slug
        ]);

        return \response()->json($this->category,Response::HTTP_OK);
    }

    /**
     * @return JsonResponse
     */
    public function remove(): JsonResponse
    {
        $this->category->delete();

        return \response()->json([
            "category" => $this->category->title,
            "deleted" => true
        ]);
    }
}
