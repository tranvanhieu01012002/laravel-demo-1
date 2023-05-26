<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Request $request): bool
    {
        return $request->route("id") == Auth::id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // "name" =>  $request->hasFile("image") ? "nullable" : "required|string",
            // "image" => "mimes:png,jpg|max:8129|file|required"
            "image" => "mimes:png,jpg|max:8129|file|required"
        ];
    }

    protected function failedValidation(Validator $validator)
    {

        $errors = $validator->errors();
        throw new HttpResponseException(
            response()->json($errors->toArray(), 422)
        );
    }
}
