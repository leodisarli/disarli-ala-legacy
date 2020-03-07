<?php

namespace App\Helpers;

use App\Exceptions\Custom\PaginateNotFoundException;

class PaginatorHelper
{
    private $size;

    /**
     * Constructor
     * @param int $size
     */
    public function __construct(
        int $size = 25
    ) {
        $this->size = $size;
    }

    /**
     * Replace break rows and multiples spaces for single ones
     * @param string $selectReceived
     * @return string
     */
    private function replaceRowsAndSpaces(
        string $select
    ) {
        return preg_replace('!\s+!', ' ', $select);
    }

    /**
     * Get position to keep on select
     * @param string $select
     * @param int/bool $positionFrom
     * @param int/bool $positionTo
     * @return string
     */
    private function getKeepPosition(
        string $select,
        $positionFrom,
        $positionTo
    ) {
        if ($positionTo) {
             return trim(substr($select, $positionFrom, $positionTo - $positionFrom));
        }
        return trim(substr($select, $positionFrom));
    }

    /**
     * Get count select
     * @param string $select select query
     * @param string $table table name
     * @return string
     */
    public function getCountSelect(
        string $select,
        string $table
    ) {
        $positionFrom = strpos($select, 'FROM ['.$table.']');
        $positionTo = strpos($select, 'ORDER BY');

        $keep = $this->getKeepPosition($select, $positionFrom, $positionTo);
        
        $over = '';
        if (strpos(strtoupper($select), "GROUP BY") !== false) {
            $over = 'OVER ()';
        }
        $countSelect = 'SELECT COUNT(1) '.$over.' as [total] '.$keep;
        
        $countSelect = $this->replaceRowsAndSpaces($countSelect);
        return $countSelect;
    }

    /**
     * Get paginated select query
     * @param string $select select query
     * @param int $page page of query
     * @return string
     */
    public function paginateSelect(
        string $select,
        int $page
    ) {
        $offset = (($page - 1) * $this->size);
        $select .= ' OFFSET '.$offset.' ROWS';
        $select .= ' FETCH NEXT '.$this->size.' ROWS ONLY';

        $select = $this->replaceRowsAndSpaces($select);

        return $select;
    }
    
    /**
     * Get make paginator array
     * @param int $page page of query
     * @param int $totalRows total de linhas
     * @return array
     */
    public function makePaginator(
        int $page,
        int $totalRows
    ) {
        $totalPages = ceil($totalRows / $this->size);

        if ($page > $totalPages && $totalRows > 0) {
            throw new PaginateNotFoundException();
        }
        $paginator = [
            'page' => $page,
            'size' => $this->size,
            'totalPages' => $totalPages,
            'totalRows' => $totalRows,
        ];
        return $paginator;
    }
}
