<?php

namespace App\Http\Requests;

use App\Enums\Entities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class PostRequest extends FormRequest
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
    #[ArrayShape(["subcategory_id" => "string", "title" => "string[]", "desc" => "string[]", "slug" => "string[]", "img" => "array", "entity" => "array", "lyric" => "string[]"])] public function rules()
    {
        return [
            "title" => "required",
            "desc" => "nullable | string",
            "slug" => "nullable | string",
            'entity' => "required | ".Rule::in([Entities::SONG->getEntity(),Entities::BLOG->getEntity(),
                    Entities::ABOUT_US->getEntity(),Entities::PODCAST->getEntity()]),
            "artist" => "nullable | string",
            "img" => "nullable | file | mimes:jpg,jpeg,png,gif | max : 6000",
            "song" => "nullable | file | mimes : mp3,mp4",
            "tags" => "nullable | array"
        ];
    }
}
