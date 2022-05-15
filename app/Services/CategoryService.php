<?php

namespace App\Services;

use App\Interfaces\ICategory;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryService implements ICategory
{

    protected Category $category;

    public function __construct()
    {
        $this->category = new Category();
    }

    /**
     * @return JsonResponse
     */
    public function findAllCategories(): JsonResponse
    {
       return response()->json($this->category::all());
    }

    /**
     * @param Category $category
     * @return JsonResponse
     */
    public function findById(Category $category): JsonResponse
    {
        return response()->json($category);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createCategory(Request $request): JsonResponse
    {
        $create = $this->category::create($request->only([
            "title","description","slug"
        ]));

        return response()->json($create,Response::HTTP_CREATED);
    }

    /**
     * @param Category $category
     * @param Request $request
     * @return JsonResponse
     */
    public function updateCategory(Category $category, Request $request): JsonResponse
    {
        $category->update([
            "title" => $request->input("title") ?? $category->title,
            "description" => $request->input("description") ?? $category->description,
            "slug" => $request->input("slug") ?? $category->slug
        ]);

        return \response()->json($category,Response::HTTP_OK);
    }

    /**
     * @param Category $category
     * @return JsonResponse
     */
    public function deleteCategory(Category $category): JsonResponse
    {
        $category->delete();

        return \response()->json([
            "category" => $category,
            "deleted" => true
        ]);
    }
}
