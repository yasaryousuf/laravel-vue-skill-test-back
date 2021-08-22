<?php

namespace App\Http\Requests\User;

use App\Traits\ApiTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class UserUpdateRequest extends FormRequest
{
    use ApiTrait;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($this->getFailureApiResponseArray($validator->errors()->first()), Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
