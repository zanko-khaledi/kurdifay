<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    protected CategoryService $categoryService;


    public function __construct()
    {
        $this->categoryService = new CategoryService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
       return  $this->categoryService->findAllCategories();
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
     * @param CategoryService $request
     * @return JsonResponse
     */
    public function store(CategoryService $request): JsonResponse
    {
        return  $this->categoryService->createCategory($request);
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return JsonResponse
     */
    public function show(Category $category): JsonResponse
    {
        return  $this->categoryService->findById($category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Category $category
     * @return void
     */
    public function edit(Category $category)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Category $category
     * @return JsonResponse
     */
    public function update(Category $category,Request $request): JsonResponse
    {
        return  $this->categoryService->updateCategory($category->id,$request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return JsonResponse
     */
    public function destroy(Category $category): JsonResponse
    {
       return  $this->categoryService->deleteCategory($category->id);
    }
}
