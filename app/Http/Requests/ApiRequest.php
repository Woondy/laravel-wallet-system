<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\RequestValidationTrait;

class ApiRequest extends FormRequest
{
    use RequestValidationTrait;
}
