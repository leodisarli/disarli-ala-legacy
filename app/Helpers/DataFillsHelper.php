<?php

namespace App\Helpers;

class DataFillsHelper
{
    /**
     * Prepare data to update
     * @param array $data
     * @param Mapper $mapper
     * @param string $justFill
     * @return array
     */
    public function fill(
        array $data,
        $mapper,
        string $justFill = null
    ) {
        $result = [];
        foreach ($data as $row => $value) {
            $key = array_search($row, $mapper->map());
            if (!empty($justFill)) {
                $start = substr($key, 0, strlen($justFill));
                if ($start != $justFill) {
                    $key = null;
                }
            }
            if (!empty($key)) {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    /**
     * Fill data by array of fields
     * @param array $data
     * @param array $fields
     * @return array
     */
    public function fillDataFields(
        array $data,
        array $fields
    ) {
        $result = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $fields)) {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}
