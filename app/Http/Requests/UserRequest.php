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
        return $request->get("id") === Auth::id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string",
        ];
    }

    protected function failedValidation(Validator $validator)
    {

        $errors = $validator->errors();
        throw new HttpResponseException(
            $this->responseErrorWithData($errors->toArray(), 422)
        );
    }
}
