<?php

namespace App\Helpers;

use \Mockery;
use App\Helpers\PaginatorHelper;
use PHPUnit\Framework\TestCase;

class PaginatorHelperTest extends TestCase
{
    /**
     * @covers \App\Helpers\PaginatorHelper::__construct
     */
    public function testPaginatorHelperCanBeInstantiated()
    {
        $paginator = new PaginatorHelper(25);
        $this->assertInstanceOf(PaginatorHelper::class, $paginator);
    }

    /**
     * @covers \App\Helpers\PaginatorHelper::getCountSelect
     * @covers \App\Helpers\PaginatorHelper::getKeepPosition
     * @covers \App\Helpers\PaginatorHelper::replaceRowsAndSpaces
     */
    public function testGetCountSelectWithOrderBy()
    {
        $select =
            "SELECT
                id
            FROM [entity]
            WHERE [field] = :field AND
                [field2] >= :field2 AND
                [field3] <= :field3
            ORDER BY created DESC";

        $resultExpected = preg_replace(
            '!\s+!',
            ' ',
            "SELECT COUNT(1) as [total]
            FROM [entity]
            WHERE [field] = :field AND
                [field2] >= :field2 AND
                [field3] <= :field3"
        );
            
        $paginator = new PaginatorHelper(25);
        $selectCount = $paginator->getCountSelect($select, 'entity');
        
        $this->assertInstanceOf(PaginatorHelper::class, $paginator);
        $this->assertEquals($selectCount, $resultExpected);
    }

    /**
     * @covers \App\Helpers\PaginatorHelper::getCountSelect
     * @covers \App\Helpers\PaginatorHelper::getKeepPosition
     * @covers \App\Helpers\PaginatorHelper::replaceRowsAndSpaces
     */
    public function testGetCountSelectWithGroupBy()
    {
        $select = "SELECT [id], [field] FROM [entity] GROUP BY [field]";
        $resultExpected = preg_replace(
            '!\s+!',
            ' ',
            "SELECT COUNT(1) OVER () as [total]
            FROM [entity]
            GROUP BY [field]"
        );
            
        $paginator = new PaginatorHelper(25);
        $selectCount = $paginator->getCountSelect($select, 'entity');
        
        $this->assertInstanceOf(PaginatorHelper::class, $paginator);
        $this->assertEquals($selectCount, $resultExpected);
    }

    /**
     * @covers \App\Helpers\PaginatorHelper::getCountSelect
     * @covers \App\Helpers\PaginatorHelper::getKeepPosition
     * @covers \App\Helpers\PaginatorHelper::replaceRowsAndSpaces
     */
    public function testGetCountSelectWithoutOrderBy()
    {
        $select =
            "SELECT
                [id]
            FROM [entity]
            WHERE [field] = :field AND
                [field2] >= :field2 AND
                [field3] <= :field3";

        $resultExpected = preg_replace(
            '!\s+!',
            ' ',
            "SELECT COUNT(1) as [total]
            FROM [entity]
            WHERE [field] = :field AND
                [field2] >= :field2 AND
                [field3] <= :field3"
        );
            
        $paginator = new PaginatorHelper();
        $selectCount = $paginator->getCountSelect($select, 'entity');
        
        $this->assertInstanceOf(PaginatorHelper::class, $paginator);
        $this->assertEquals($selectCount, $resultExpected);
    }

    /**
     * @covers \App\Helpers\PaginatorHelper::paginateSelect
     * @covers \App\Helpers\PaginatorHelper::replaceRowsAndSpaces
     */
    public function testPaginateSelect()
    {
        $page = 1;
        $select =
            "SELECT
                [id]
            FROM [entity]
            WHERE [field] = :field AND
                [field2] >= :field2 AND
                [field3] <= :field3
            ORDER BY [created]";

        $resultExpected = preg_replace(
            '!\s+!',
            ' ',
            "SELECT
                [id]
            FROM [entity]
            WHERE [field] = :field AND
                [field2] >= :field2 AND
                [field3] <= :field3
            ORDER BY [created] OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY"
        );

        $paginator = new PaginatorHelper();
        $selectCount = $paginator->paginateSelect($select, $page);

        $this->assertInstanceOf(PaginatorHelper::class, $paginator);
        $this->assertEquals($selectCount, $resultExpected);
    }

    /**
     * @covers \App\Helpers\PaginatorHelper::makePaginator
     */
    public function testMakePaginatorPageOne()
    {
        $page = 1;
        $rows = 110;

        $resultExpected = [
            'page' => $page,
            'size' => 25,
            'totalPages' => 5,
            'totalRows' => $rows,
        ];

        $paginator = new PaginatorHelper();
        $makePaginator = $paginator->makePaginator($page, $rows);

        $this->assertInstanceOf(PaginatorHelper::class, $paginator);
        $this->assertInternalType('array', $makePaginator);
        $this->assertArrayHasKey('page', $makePaginator);
        $this->assertArrayHasKey('size', $makePaginator);
        $this->assertArrayHasKey('totalPages', $makePaginator);
        $this->assertArrayHasKey('totalRows', $makePaginator);
        $this->assertEquals(1, $makePaginator['page']);
        $this->assertEquals(25, $makePaginator['size']);
        $this->assertEquals(5, $makePaginator['totalPages']);
        $this->assertEquals(110, $makePaginator['totalRows']);
    }

    /**
     * @covers \App\Helpers\PaginatorHelper::makePaginator
     * @expectedException App\Exceptions\Custom\PaginateNotFoundException
     */
    public function testMakePaginatorHelperPageNotFound()
    {
        $page = 6;
        $rows = 110;

        $resultExpected = [
            'page' => $page,
            'size' => 25,
            'totalPages' => 5,
            'totalRows' => $rows,
        ];

        $paginator = new PaginatorHelper();
        $makePaginator = $paginator->makePaginator($page, $rows);

        $this->assertInstanceOf(PaginatorHelper::class, $paginator);
        $this->assertInternalType('array', $makePaginator);
        $this->assertArrayHasKey('page', $makePaginator);
        $this->assertArrayHasKey('size', $makePaginator);
        $this->assertArrayHasKey('totalPages', $makePaginator);
        $this->assertArrayHasKey('totalRows', $makePaginator);
        $this->assertEquals(1, $makePaginator['page']);
        $this->assertEquals(25, $makePaginator['size']);
        $this->assertEquals(5, $makePaginator['totalPages']);
        $this->assertEquals(110, $makePaginator['totalRows']);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
