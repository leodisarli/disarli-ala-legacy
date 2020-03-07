<?php

namespace App\Refiners\Base;

use App\Refiners\Base\StdClassRefiner;

class CanTestRefine extends StdClassRefiner
{
    public $refiner;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->refiner = [
            'test_id' => ['category_id', '=', ':category_id'],
            'test_name' => ['category_name', '=', ':category_name'],
        ];
    }

    /**
     * Create a refine with refine mapper
     * @return array with refine filters
     */
    public function createRefine()
    {
        return $this->refiner;
    }
}
