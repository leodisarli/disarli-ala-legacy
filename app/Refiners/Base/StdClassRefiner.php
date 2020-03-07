<?php

namespace App\Refiners\Base;

class StdClassRefiner
{
    public $refiner;
    private $refinesArray;

    /**
     * Map the refine array and return a mapped array
     * @param array $data optional array to refine query
     * @return array with refine filters
     */
    private function refinerData(array $data) : array
    {
        $arr = [];
        foreach ($data as $key => $row) {
            unset($row);
            if (array_key_exists($key, $this->refiner)) {
                $arr[] = $this->refinerRow($this->refiner, $key);
            }
        }
        return $arr;
    }

    /**
     * Map the refine params and return a mapped array
     * @param array $data optional params array to refine query
     * @return array with refine params
     */
    private function refinerParams(array $data) : array
    {
        $arr = [];
        foreach ($data as $key => $row) {
            if (array_key_exists($key, $this->refiner)) {
                $arrKey = $this->paramsRow($this->refiner, $key);
                $arr[$arrKey] = $row;
            }
        }
        return $arr;
    }

    /**
     * Map the refine params and return a row with equivalent value
     * @param array $refiner optional params array to refine query
     * @param string $row array key name
     * @return string with refine params
     */
    private function paramsRow(array $refiner, string $row) : string
    {
        $result = '';
        foreach ($refiner as $key => $value) {
            if ($key == $row) {
                $result = str_replace(':', '', $value[2]);
            }
        }
        return $result;
    }

    /**
     * Map the refine array and return a row with equivalent value
     * @param array $refiner optional array array to refine query
     * @param string $row array key name
     * @return array with refine options
     */
    private function refinerRow(array $refiner, string $row) : array
    {
        $newArray = [];
        foreach ($refiner as $key => $value) {
            if ($key == $row) {
                $newArray = $value;
            }
        }
        return $newArray;
    }

    /**
     * Refine the array data
     * @param array $data array to refine query
     * @return array with refined mapped
     */
    public function refine(array $data) : array
    {
        $this->refinesArray = $this->refinerData($data);
        return $this->refinesArray;
    }

    /**
     * Refine the array data
     * @param array $data params to refine query
     * @return array with refined mapped params
     */
    public function params(array $data) : array
    {
        $this->refinesArray = $this->refinerParams($data);
        return $this->refinesArray;
    }
}
