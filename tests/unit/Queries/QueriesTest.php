<?php

namespace App\Queries;

use \Mockery;
use App\Queries\Queries;
use App\Exceptions\Custom\InvalidOrderArrayException;
use App\Exceptions\Custom\InvalidOrderOperatorException;
use App\Exceptions\Custom\InvalidRefineArrayException;
use App\Exceptions\Custom\InvalidRefineOperatorException;
use App\Exceptions\Custom\InvalidRefineParamsException;
use PHPUnit\Framework\TestCase;

class QueriesTest extends TestCase
{
    /**
     * @covers \App\Queries\Queries::getQuery
     */
    public function testGetQuery()
    {
        $params = [
            'id' => 1,
        ];
        $queries = new Queries();
        $queries->queries['test'] =
            "SELECT *
                FROM [entity]
                WHERE [id] = :id";
        $expectedResult = 'SELECT * FROM [entity] WHERE [id] = :id';
        $result = $queries->getQuery('test');

        $this->assertInternalType('string', $result);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @covers \App\Queries\Queries::getQuery
     * @covers \App\Queries\Queries::getRefines
     * @covers \App\Queries\Queries::mountRefineString
     * @covers \App\Queries\Queries::validateRefines
     */
    public function testGetQueryWithRefines()
    {
        $params = [
            'id' => 1,
        ];
        $refines = [
            ['[status]','=',':status']
        ];
        $queries = new Queries();
        $queries->queries['test'] =
            "SELECT *
                FROM [entity]
                WHERE [id] = :id
                {{refines}}";
        $expectedResult = 'SELECT * FROM [entity] WHERE [id] = :id AND [status] = :status';
        $result = $queries->getQuery('test', $refines);
        $this->assertInternalType('string', $result);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @covers \App\Queries\Queries::getQuery
     * @covers \App\Queries\Queries::getRefines
     * @covers \App\Queries\Queries::mountRefineString
     * @covers \App\Queries\Queries::validateRefines
     * @expectedException App\Exceptions\Custom\InvalidRefineArrayException
     */
    public function testGetQueryWithRefinesAndGetInvalidArrayException()
    {
        $params = [
            'id' => 1,
        ];
        $refines = [
            ['[status]', '=']
        ];
        $queries = new Queries();
        $queries->queries['test'] =
            "SELECT *
                FROM [entity]
                WHERE [id] = :id
                {{refines}}";
        $this->expectException(InvalidRefineArrayException::class);
        $result = $queries->getQuery('test', $refines);
    }

    /**
     * @covers \App\Queries\Queries::getQuery
     * @covers \App\Queries\Queries::getRefines
     * @covers \App\Queries\Queries::mountRefineString
     * @covers \App\Queries\Queries::validateRefines
     * @expectedException App\Exceptions\Custom\InvalidRefineOperatorException
     */
    public function testGetQueryWithRefinesAndGetInvalidOperatorException()
    {
        $params = [
            'id' => 1,
        ];
        $refines = [
            ['[cli_cod]', 'LIKE', ':cli_cod']
        ];
        $queries = new Queries();
        $queries->queries['test'] =
            "SELECT *
                FROM entity
                WHERE [id] = :id
                {{refines}}";
        $this->expectException(InvalidRefineOperatorException::class);
        $result = $queries->getQuery('test', $refines);
    }

    /**
     * @covers \App\Queries\Queries::getQuery
     * @covers \App\Queries\Queries::getRefines
     * @covers \App\Queries\Queries::mountRefineString
     * @covers \App\Queries\Queries::validateRefines
     * @expectedException App\Exceptions\Custom\InvalidRefineParamsException
     */
    public function testGetQueryWithRefinesAndGetInvalidParamsException()
    {
        $params = [
            'id' => 1,
        ];
        $refines = [
            ['[status]', '=', '122']
        ];
        $queries = new Queries();
        $queries->queries['test'] =
            "SELECT *
                FROM [entity]
                WHERE [id] = :id
                {{refines}}";
        $this->expectException(InvalidRefineParamsException::class);
        $result = $queries->getQuery('test', $refines);
    }

    /**
     * @covers \App\Queries\Queries::getQuery
     * @covers \App\Queries\Queries::getOrders
     * @covers \App\Queries\Queries::mountOrderString
     * @covers \App\Queries\Queries::validateOrders
     */
    public function testGetQueryWithOrders()
    {
        $params = [
            'id' => 1,
        ];
        $order = [
            ['[status]','DESC']
        ];
        $queries = new Queries();
        $queries->queries['test'] =
            "SELECT *
                FROM [entity]
                WHERE [id] = :id
                {{orders}}";
        $expectedResult = 'SELECT * FROM [entity] WHERE [id] = :id ORDER BY [status] DESC';
        $result = $queries->getQuery('test', [], $order);

        $this->assertInternalType('string', $result);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @covers \App\Queries\Queries::getQuery
     * @covers \App\Queries\Queries::getOrders
     * @covers \App\Queries\Queries::mountOrderString
     * @covers \App\Queries\Queries::validateOrders
     */
    public function testGetQueryWithManyOrders()
    {
        $params = [
            'id' => 1,
        ];
        $order = [
            ['[status]','DESC'],
            ['[name]','ASC'],
        ];
        $queries = new Queries();
        $queries->queries['test'] =
            "SELECT *
                FROM [entity]
                WHERE [id] = :id
                {{orders}}";
        $expectedResult = 'SELECT * FROM [entity] WHERE [id] = :id ORDER BY [status] DESC , [name] ASC';
        $result = $queries->getQuery('test', [], $order);
        $this->assertInternalType('string', $result);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @covers \App\Queries\Queries::getQuery
     * @covers \App\Queries\Queries::getOrders
     * @covers \App\Queries\Queries::mountOrderString
     * @covers \App\Queries\Queries::validateOrders
     * @expectedException App\Exceptions\Custom\InvalidOrderArrayException
     */
    public function testGetQueryWithOrdersAndGetInvalidArrayException()
    {
        $params = [
            'id' => 1,
        ];
        $order = [
            ['[id]']
        ];
        $queries = new Queries();
        $queries->queries['test'] =
            "SELECT *
                FROM [entity]
                WHERE [id] = :id
                {{orders}}";
        $this->expectException(InvalidOrderArrayException::class);
        $result = $queries->getQuery('test', [], $order);
    }

    /**
     * @covers \App\Queries\Queries::getQuery
     * @covers \App\Queries\Queries::getOrders
     * @covers \App\Queries\Queries::mountOrderString
     * @covers \App\Queries\Queries::validateOrders
     * @expectedException App\Exceptions\Custom\InvalidOrderOperatorException
     */
    public function testGetQueryWithOrdersAndGetInvalidOperatorException()
    {
        $params = [
            'id' => 1,
        ];
        $order = [
            ['[id]', 'ASFASF']
        ];
        $queries = new Queries();
        $queries->queries['test'] =
            "SELECT *
                FROM [entity]
                WHERE [id] = :id
                {{orders}}";
        $this->expectException(InvalidOrderOperatorException::class);
        $result = $queries->getQuery('test', [], $order);
    }
}
