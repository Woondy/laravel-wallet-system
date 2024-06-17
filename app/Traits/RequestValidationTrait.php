<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Http\Resources\ApiResource;

trait RequestValidationTrait
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(ApiResource::error($validator->errors()->first()));
    }
}
