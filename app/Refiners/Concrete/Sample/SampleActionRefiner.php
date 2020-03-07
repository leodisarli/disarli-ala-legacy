<?php

namespace App\Refiners\Concrete\Sample;

use App\Refiners\Base\StdClassRefiner;

class SampleActionRefiner extends StdClassRefiner
{
    public $refiner;

    public function __construct()
    {
        $this->refiner = [
            'field_name' => [
                'field',
                '=',
                ':field',
            ],
        ];
    }

    public function createRefine()
    {
        return $this->refiner;
    }
}
