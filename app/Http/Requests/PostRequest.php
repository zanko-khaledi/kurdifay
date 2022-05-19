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
            "subcategory_id" => "required",
            "title" => ["required","string"],
            "desc" => ["nullable","string"],
            "slug" => ["required","string"],
            "img" => "nullable | mimes:jpg,jpeg,png,gif | max : 6000",
            "artist" => "nullable | string",
            "entity" => [
                "nullable",
                Rule::in([Entities::SONG->getEntity(),Entities::BLOG->getEntity(),Entities::PODCAST->getEntity(),Entities::ABOUT_US->getEntity()])
            ],
            "lyric" => ["nullable","string"],
            "src" => "nullable | mimes:mp3,mp4"
        ];
    }
}
