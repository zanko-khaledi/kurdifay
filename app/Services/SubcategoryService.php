<?php

namespace App\Services;

use App\Interfaces\ISubcategory;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubcategoryService implements ISubcategory
{

    protected Subcategory $subcategory;

    public function __construct(Subcategory $subcategory)
    {
        $this->subcategory = $subcategory;
    }

    /**
     * @return JsonResponse
     */
    public function findAllSubCategories(): JsonResponse
    {
        return response()->json($this->subcategory::all());
    }

    /**
     * @param Subcategory $subcategory
     * @return JsonResponse
     */
    public function findSubcategoryById(Subcategory $subcategory): JsonResponse
    {
        return response()->json($subcategory);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createSubCategory(Request $request): JsonResponse
    {
        $category_id = $request->input("category_id");

        $category = Category::all()->find($category_id);

        if($category){
             $subcategory_created = $category->subCategories()->create([
                "title" => $request->input("title"),
                "description" => $request->input("description"),
                "slug" => $request->input("slug")
            ]);

            return response()->json($subcategory_created,Response::HTTP_CREATED);
        }else{
            return \response()->json([
                "message" => "Category_ud " .$category_id . " was not founded!"
            ],Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @param Subcategory $subcategory
     * @param Request $request
     * @return JsonResponse
     */
    public function updateSubcategory(Subcategory $subcategory, Request $request): JsonResponse
    {
        $subcategory->update([
            "category_id" => $request->input('category_id') ?? $subcategory->category_id,
            "title" => $request->input("title") ?? $subcategory->title,
            "description" => $request->input("description") ?? $subcategory->description,
            "slug" => $request->input("slug") ?? $subcategory->slug
        ]);

        return \response()->json([
            "subcategory" => $subcategory,
            "updated" => true
        ],Response::HTTP_OK);
    }

    /**
     * @param Subcategory $subcategory
     * @return JsonResponse
     */
    public function deleteSubcategory(Subcategory $subcategory): JsonResponse
    {
        $subcategory->delete();

        return \response()->json([
            "subcategory" => $subcategory,
            "deleted" => true
        ],Response::HTTP_OK);
    }
}
