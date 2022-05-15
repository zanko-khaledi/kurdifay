<?php

namespace App\Http\Requests;

use App\Rules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class UserRequest extends FormRequest
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
    #[ArrayShape(["name" => "string", "email" => "string", "password" => "string", "roles" => "array"])] public function rules(): array
    {
        return [
            "name" => "required | string",
            "email" => "required | email",
            "password" => "required",
            "roles" => [
                "required", Rule::in([ Rules::USER->getRules() , Rules::ADMIN->getRules()])
            ]
        ];
    }
}
