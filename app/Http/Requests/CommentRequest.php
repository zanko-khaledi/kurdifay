<?php

namespace App\Http\Requests;

use App\Enums\CommentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class CommentRequest extends FormRequest
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
    #[ArrayShape(["post_id" => "string", "email" => "string", "name" => "string", "comment" => "string"])] public function rules()
    {
        return [
            "post_id" => "required | int",
            "email" => "required | email",
            "name" => "required | string",
            "comment" => "string",
            "entity" => "required | ".Rule::in([CommentType::COMMENT->getType(),CommentType::REPLAY->getType()])
        ];
    }
}
