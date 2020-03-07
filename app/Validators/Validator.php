<?php

namespace App\Validators;

use Illuminate\Http\Request;

class Validator
{
    public function validate(array $request, string $validatorClass = null)
    {
        $instance = new $validatorClass();
        return $instance->validate($request);
    }
}
