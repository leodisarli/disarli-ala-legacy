<?php

namespace App\Helpers;

use \Mockery;
use App\Helpers\DataFillsHelper;
use PHPUnit\Framework\TestCase;

class DataFillsHelperTest extends TestCase
{
    /**
     * @covers \App\Helpers\DataFillsHelper::fill
     */
    public function testDataFillsHelper()
    {
        $data = [
            'fantasy' => 'Fantasy',
            'taxid' => '12345678911',
            'telephone' => '15998563254',
            'telephone2' => '15998563255',
            'cellphone' => '15998563256',
        ];

        $map = [
            'cli_fantasia' => 'fantasy',
            'cli_cpfcnpj' => 'taxid',
            'cli_tel' => 'telephone',
            'cli_tel2' => 'telephone2',
            'cli_cel' => 'cellphone',
        ];

        $mapperMock = Mockery::mock(SetUserMap::class)
            ->shouldReceive('map')
            ->times(5)
            ->withAnyArgs()
            ->andReturn($map)
            ->getMock();
        
        $dataFills = new DataFillsHelper();
        $fill = $dataFills->fill($data, $mapperMock);
        $this->assertInternalType('array', $fill);
        $this->assertEquals('Fantasy', $fill['cli_fantasia']);
    }

    /**
     * @covers \App\Helpers\DataFillsHelper::fill
     */
    public function testDataFillsHelperWithJustFills()
    {
        $data = [
            'fantasy' => 'Fantasy',
            'taxid' => '12345678911',
            'telephone' => '15998563254',
            'telephone2' => '15998563255',
            'cellphone' => '15998563256',
            'content_id' => '1234',
        ];

        $map = [
            'cli_fantasia' => 'fantasy',
            'cli_cpfcnpj' => 'taxid',
            'cli_tel' => 'telephone',
            'cli_tel2' => 'telephone2',
            'cli_cel' => 'cellphone',
            'cnt_cod' => 'content_id',
        ];

        $mapperMock = Mockery::mock(SetUserMap::class)
            ->shouldReceive('map')
            ->times(6)
            ->withAnyArgs()
            ->andReturn($map)
            ->getMock();
        
        $dataFills = new DataFillsHelper();
        $fill = $dataFills->fill($data, $mapperMock, 'cli');
        $this->assertInternalType('array', $fill);
        $this->assertEquals('Fantasy', $fill['cli_fantasia']);
    }

    /**
     * @covers \App\Helpers\DataFillsHelper::fillDataFields
     */
    public function testFillDataFields()
    {
        $data = [
            'fantasy' => 'Fantasy',
            'taxid' => '12345678911',
            'telephone' => '15998563254',
            'telephone2' => '15998563255',
            'cellphone' => '15998563256',
            'content_id' => '1234',
        ];

        $fields = [
            'fantasy',
            'taxid',
            'telephone',
        ];

        $dataFills = new DataFillsHelper();
        $fill = $dataFills->fillDataFields($data, $fields);
        $this->assertInternalType('array', $fill);
        $this->assertEquals('Fantasy', $fill['fantasy']);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
