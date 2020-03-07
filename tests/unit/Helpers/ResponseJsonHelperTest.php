<?php

namespace App\Helpers;

use \Mockery;
use App\Helpers\ResponseJsonHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use PHPUnit\Framework\TestCase;

class ResponseJsonHelperTest extends TestCase
{
    /**
     * @covers \App\Helpers\ResponseJsonHelper::setStartProfiler
     */
    public function testSetStartProfiler()
    {
        $responseJson = new ResponseJsonHelper();
        $responseJson->setStartProfiler();
        $response = ResponseJsonHelper::$profiler;

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('start', $response);
        $this->assertNotEmpty($response['start']);
    }

    /**
     * @covers \App\Helpers\ResponseJsonHelper::setFinishProfiler
     */
    public function testSetFinishProfiler()
    {
        $responseJson = new ResponseJsonHelper();
        $responseJson->setFinishProfiler();
        $response = ResponseJsonHelper::$profiler;

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('finish', $response);
        $this->assertArrayHasKey('process', $response);
        $this->assertNotEmpty($response['finish']);
        $this->assertNotEmpty($response['process']);
    }

     /**
     * @covers \App\Helpers\ResponseJsonHelper::setTokenToProfiler
     */
    public function testSetTokenToProfiler()
    {
        $responseJson = new ResponseJsonHelper();
        $responseJson->setTokenToProfiler('token', '2018-07-25 12:32:12');
        $response = ResponseJsonHelper::$profiler;

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('token', $response);
        $this->assertNotEmpty($response['token']);
        $this->assertEquals('token', $response['token']);
    }

    /**
     * @covers \App\Helpers\ResponseJsonHelper::response
     */
    public function testResponse()
    {
        $data = [
            'field' => 'value',
            'field2' => 'value2',
        ];

        $responseJson = new ResponseJsonHelper();
        $response = $responseJson->response($data);
        $data = ResponseJsonHelper::$data;
        $profiler = ResponseJsonHelper::$profiler;

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('success', $data);
        $this->assertArrayHasKey('version', $data);
        $this->assertEquals(true, $data['success']);
        $this->assertInternalType('array', $profiler);
    }

    /**
     * @covers \App\Helpers\ResponseJsonHelper::response
     */
    public function testResponseWithPaginatorHelper()
    {
        $data = [
            'field' => 'value',
            'field2' => 'value2',
        ];

        $paginator = [
            'page' => 1,
            'size' => 2,
            'totalPages' => 5,
            'totalRows' => 10,
        ];

        $responseJson = new ResponseJsonHelper();
        $response = $responseJson->response($data, $paginator);
        $data = ResponseJsonHelper::$data;
        $profiler = ResponseJsonHelper::$profiler;

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('success', $data);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('paginator', $data);
        $this->assertArrayHasKey('version', $data);
        $this->assertEquals(true, $data['success']);
        $this->assertEquals('value', $data['data']['field']);
        $this->assertEquals('value2', $data['data']['field2']);
        $this->assertEquals(1, $data['paginator']['page']);
        $this->assertEquals(2, $data['paginator']['size']);
        $this->assertEquals(5, $data['paginator']['totalPages']);
        $this->assertEquals(10, $data['paginator']['totalRows']);
        $this->assertInternalType('array', $profiler);
    }

    /**
     * @covers \App\Helpers\ResponseJsonHelper::responseDelete
     */
    public function testResponseDelete()
    {
        $responseJson = new ResponseJsonHelper();
        $response = $responseJson->responseDelete();

        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @covers \App\Helpers\ResponseJsonHelper::error
     */
    public function testError()
    {
        $code = 400;
        $msg = 'Test error';
        $statusCode = 400;

        $responseJson = new ResponseJsonHelper();
        $response = $responseJson->error($code, $msg, $statusCode);
        $profiler = ResponseJsonHelper::$profiler;
        $data = ResponseJsonHelper::$data;

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertInternalType('array', $profiler);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('success', $data);
        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('details', $data);
        $this->assertArrayHasKey('version', $data);
        $this->assertEquals(false, $data['success']);
        $this->assertEquals($code, $data['code']);
        $this->assertEquals($msg, $data['details']);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
