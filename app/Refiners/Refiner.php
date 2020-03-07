<?php

namespace App\Refiners;

use Illuminate\Http\Request;

class Refiner
{
    /**
     * Refine the array data
     * @param string $refiner refiner mapper to params
     * @param array $data array to refine query
     * @return array with refined mapped
     */
    public function refine(string $refiner, $data)
    {
        $instance = new $refiner();
        return $instance->refine($data);
    }

    /**
     * Refine the array params
     * @param string $refiner refiner mapper to params
     * @param array $data params to refine query
     * @return array with refined mapped params
     */
    public function params(string $refiner, $data)
    {
        $instance = new $refiner();
        return $instance->params($data);
    }
}
