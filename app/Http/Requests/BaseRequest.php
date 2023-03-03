<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class BaseRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                    'success'   => false,
                    'code'      => 422,
                    'message'   => $validator->getMessageBag()->first(),
                    'errors'    => $validator->getMessageBag()
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
