<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class SubcategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(["category_id" => "string", "title" => "string", "description" => "string", "slug" => "string"])] public function rules(): array
    {
        return [
            "category_id" => "required",
            "title" => "required",
            "description" => "required",
            "slug" => "required",
            "img" => "nullable | mimes : jpeg,jpg,png,gif | max: 6000"
        ];
    }
}
