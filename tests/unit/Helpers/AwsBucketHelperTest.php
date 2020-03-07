<?php

namespace App\Helpers;

use \Mockery;
use Aws\S3\S3Client;
use PHPUnit\Framework\TestCase;

class AwsBucketHelperTest extends TestCase
{
    /**
     * @covers \App\Helpers\AwsBucketHelper::__construct
     */
    public function testAwsBucketHelperCanBeInstanciated()
    {
        $sqsClientSpy = Mockery::spy(S3Client::class);
        $helper = new AwsBucketHelper($sqsClientSpy);
        $this->assertInstanceOf(AwsBucketHelper::class, $helper);
    }

    /**
     * @covers \App\Helpers\AwsBucketHelper::putFile
     */
    public function testPutFile()
    {
        file_put_contents('storage/report/file_name.ext', 'test');
        $result = [
            'ObjectURL' => 'https://url/file.ext',
        ];

        $sqsClientMock = Mockery::mock(S3Client::class);
        $sqsClientMock->shouldReceive('putObject')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf();

        $sqsClientMock->shouldReceive('toArray')
            ->once()
            ->withAnyArgs()
            ->andReturn($result);


        $helper = new AwsBucketHelper($sqsClientMock);
        $file = $helper->putFile('storage/report/file_name.ext', 'ext');
        $this->assertEquals($file, 'https://url/file.ext');
    }

    /**
     * @covers \App\Helpers\AwsBucketHelper::listFiles
     */
    public function testListFiles()
    {
        $result = [
        ];

        $sqsClientMock = Mockery::mock(S3Client::class);
        $sqsClientMock->shouldReceive('listObjects')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf();

        $sqsClientMock->shouldReceive('toArray')
            ->once()
            ->withAnyArgs()
            ->andReturn($result);


        $helper = new AwsBucketHelper($sqsClientMock);
        $list = $helper->listFiles();
        $this->assertEquals($list, []);
    }

    /**
     * @covers \App\Helpers\AwsBucketHelper::deleteFile
     */
    public function testDeleteFile()
    {
        $result = 'https://url/file.ext';

        $sqsClientMock = Mockery::mock(S3Client::class);
        $sqsClientMock->shouldReceive('deleteObject')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf();

        $sqsClientMock->shouldReceive('toArray')
            ->once()
            ->withAnyArgs()
            ->andReturn($result);


        $helper = new AwsBucketHelper($sqsClientMock);
        $deleted = $helper->deleteFile('file.ext');
        $this->assertEquals($deleted, $result);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
