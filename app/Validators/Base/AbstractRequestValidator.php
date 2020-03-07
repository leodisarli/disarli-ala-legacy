<?php

namespace App\Validators\Base;

use App\Exceptions\Custom\InputValidationException;
use Illuminate\Support\Facades\Validator;

abstract class AbstractRequestValidator
{
    /**
     * Validade data
     * @param array $data
     * @return array $fields
     */
    public function validate(array $data)
    {
        $fields = $this->execute($data);
        return $fields;
    }
    
    /**
     * Test validation
     * @param array $data
     * @return array $fields
     */
    public function testValidation(array $data)
    {
        $fields = $this->execute($data);
        return $fields;
    }
    
    /**
     * Validade
     * @param array $data
     * @throws InputValidationException
     * @return array $fields
     */
    private function execute(array $data)
    {
        $rules = $this->getRules();
        $validator = Validator::make(
            $data,
            $rules,
            $this->getMessages()
        );
        
        if ($validator->fails()) {
            throw new InputValidationException($validator->messages()->toArray());
        }
        
        $fields = array_keys($rules);
        return $fields;
    }
}
