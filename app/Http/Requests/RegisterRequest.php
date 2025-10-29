<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Services\ResponseService;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:6|confirmed',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new ResponseService();
        throw new HttpResponseException(
            $response->error(422, 'Validation failed', $validator->errors())
        );
    }
}
