<?php

namespace App\Interfaces;

use App\Models\Category;
use Illuminate\Http\Request;

interface ICategory
{

    public function findAllCategories();

    public function findById(Category $category);

    public function createCategory(Request $request);

    public function updateCategory(Category $category,Request $request);

    public function deleteCategory(Category $category);
}
