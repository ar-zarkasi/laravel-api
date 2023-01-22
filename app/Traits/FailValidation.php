<?php namespace App\Traits;

use Illuminate\Validation\Validator;
use App\Traits\ResponseApi;

trait FailValidation {
    use ResponseApi;

    public function failedValidation(Validator $validator) {
        // Perform your response 
        // by default it will throw ValidationException.
        return $this->error($validator->errors(), 422);
    }
}