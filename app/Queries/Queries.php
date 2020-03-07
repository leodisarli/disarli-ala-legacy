<?php

namespace App\Queries;

use App\Exceptions\Custom\InvalidOrderArrayException;
use App\Exceptions\Custom\InvalidOrderOperatorException;
use App\Exceptions\Custom\InvalidRefineArrayException;
use App\Exceptions\Custom\InvalidRefineOperatorException;
use App\Exceptions\Custom\InvalidRefineParamsException;

class Queries
{
    const ACCEPTABLE_REFINE_OPERATORS = [
        '<',
        '=',
        '>',
        '<>',
        '!=',
        '<=',
        '>=',
        'like'
    ];
    const ACCEPTABLE_ORDER_OPERATORS = [
        'ASC',
        'DESC',
    ];

    public $queries;

    /**
     * Return query string by the query name
     * @param string $query query name
     * @param array $refines optional array to filters query
     * @param array $orders optional array to order query
     * @return string with query string
     */
    public function getQuery(string $query, array $refines = [], array $orders = []) : string
    {
        $result = $this->queries[$query];
        $result = $this->getRefines($result, $refines);
        $result = $this->getOrders($result, $orders);
        $result = preg_replace('!\s+!', ' ', $result);
        $result = trim($result);
        return $result;
    }

    /**
     * Return string with refine data
     * @param string $query query string
     * @param array $refines optional array to filters query
     * @return string with query filters string
     */
    private function getRefines(string $query, array $refines = []) : string
    {
        $stringRefine = '';
        foreach ($refines as $refine) {
            $this->validateRefines($refine);
            $stringRefine .= $this->mountRefineString($refine);
        }
        $result = preg_replace('/{{refines}}/', $stringRefine, $query);
        return $result;
    }

    /**
     * Return string with order data
     * @param string $query query string
     * @param array $orders optional array to order query
     * @return string with query order string
     */
    private function getOrders(string $query, array $orders = []) : string
    {
        $stringOrder = '';
        if (count($orders) > 0) {
            $stringOrder = ', ';
            if (strpos(strtoupper($query), 'ORDER BY') === false) {
                $stringOrder = ' ORDER BY ';
            }
            $count = 0;
            foreach ($orders as $order) {
                $this->validateOrders($order);
                if ($count > 0) {
                    $stringOrder .= ', ';
                }
                $stringOrder .= $this->mountOrderString($order);
                $count++;
            }
        }
        $result = preg_replace('/{{orders}}/', $stringOrder, $query);
        return $result;
    }

    /**
     * Validate the refine array
     * @param array $refine optional array to refine query
     * @return bool
     */
    private function validateRefines(array $refine = []) : bool
    {
        if (count($refine) != 3) {
            throw new InvalidRefineArrayException;
        }
        if (!in_array($refine[1], self::ACCEPTABLE_REFINE_OPERATORS)) {
            throw new InvalidRefineOperatorException;
        }
        if (strpos($refine[2], ':') === false) {
            throw new InvalidRefineParamsException;
        }
        return true;
    }

    /**
     * Validate the order array
     * @param array $order optional array to order query
     * @return bool
     */
    private function validateOrders(array $order = []) : bool
    {
        if (count($order) != 2) {
            throw new InvalidOrderArrayException;
        }
        if (!in_array(strtoupper($order[1]), self::ACCEPTABLE_ORDER_OPERATORS)) {
            throw new InvalidOrderOperatorException;
        }
        return true;
    }

    /**
     * Mount the refine string
     * @param array $refine optional array to refine query
     * @return string with refine filters
     */
    private function mountRefineString(array $refine = []) : string
    {
        $refineString = ' AND ';
        foreach ($refine as $value) {
            $refineString .= $value . ' ';
        }
        return $refineString;
    }

    /**
     * Mount the order string
     * @param array $order optional array to order query
     * @return string with orders
     */
    private function mountOrderString(array $order = []) : string
    {
        $orderString = '';
        foreach ($order as $value) {
            $orderString .= $value . ' ';
        }
        return $orderString;
    }
}
