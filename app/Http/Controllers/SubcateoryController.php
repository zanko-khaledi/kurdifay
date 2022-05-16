<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubcategoryRequest;
use App\Models\Subcategory;
use App\Services\SubcategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Pure;

class SubcateoryController extends Controller
{


    protected SubcategoryService $subcategoryService;

    #[Pure] public function __construct()
    {
        $this->subcategoryService = new SubcategoryService(new Subcategory());
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->subcategoryService->findAllSubCategories();
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
    public function store(SubcategoryRequest $request): JsonResponse
    {
        return $this->subcategoryService->createSubCategory($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subcategory  $subcategory
     * @return JsonResponse
     */
    public function show(Subcategory $subcategory): JsonResponse
    {
        return $this->subcategoryService->findSubcategoryById($subcategory);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function edit(Subcategory $subcategory)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subcategory  $subcategory
     * @return JsonResponse
     */
    public function update(Request $request, Subcategory $subcategory): JsonResponse
    {
        return $this->subcategoryService->updateSubcategory($subcategory,$request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subcategory  $subcategory
     * @return JsonResponse
     */
    public function destroy(Subcategory $subcategory): JsonResponse
    {
        return $this->subcategoryService->deleteSubcategory($subcategory);
    }
}
