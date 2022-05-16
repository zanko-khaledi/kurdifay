<?php

namespace App\Interfaces;

use App\Models\Subcategory;
use Illuminate\Http\Request;

interface ISubcategory
{

    public function findAllSubCategories();

    public function findSubcategoryById(Subcategory $subcategory);

    public function createSubCategory(Request $request);

    public function updateSubcategory(Subcategory $subcategory,Request $request);

    public function deleteSubcategory(Subcategory $subcategory);
}
