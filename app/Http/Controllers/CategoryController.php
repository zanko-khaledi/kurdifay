<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    protected CategoryService $categoryService;

    public function __construct()
    {
        $this->categoryService = new CategoryService(new Category());
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
       return  $this->categoryService->findAll();
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
    public function store(CategoryService $request): JsonResponse
    {
        return  $this->categoryService->create($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return JsonResponse
     */
    public function show(Category $category): JsonResponse
    {
        $this->categoryService = new CategoryService($category);

        return  $this->categoryService->findOne();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return JsonResponse
     */
    public function update(Request $request, Category $category): JsonResponse
    {
        $this->categoryService = new CategoryService($category);

        return  $this->categoryService->update($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return JsonResponse
     */
    public function destroy(Category $category): JsonResponse
    {
        $this->categoryService= new CategoryService($category);

        return $this->categoryService->remove();
    }
}
