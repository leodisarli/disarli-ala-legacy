<?php

namespace App\Helpers;

use \Mockery;
use PHPUnit\Framework\TestCase;

class UuidHelperTest extends TestCase
{
    /**
     * @covers \App\Helpers\UuidHelper::uuid4
     */
    public function testUuid4()
    {
        $uuid = new UuidHelper();
        $uuid4 = $uuid->uuid4();
        $this->assertEquals(36, strlen($uuid4));
    }

    /**
     * @covers \App\Helpers\UuidHelper::isValid
     */
    public function testUuidIsValid()
    {
        $uuid = new UuidHelper();
        $uuid4 = $uuid->isValid('8b9c6749-867c-41f4-af6b-53689cd8722b');
        $this->assertEquals(true, $uuid4);
    }

    /**
     * @covers \App\Helpers\UuidHelper::isValid
     */
    public function testUuidIsNotValid()
    {
        $uuid = new UuidHelper();
        $uuid4 = $uuid->isValid('8b9cfas61749-8asf67c-41f4-af6b-asf53689cd8722b');
        $this->assertEquals(false, $uuid4);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
