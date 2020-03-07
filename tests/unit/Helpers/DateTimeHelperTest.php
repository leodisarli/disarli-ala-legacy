<?php

namespace App\Helpers;

use \Mockery;
use PHPUnit\Framework\TestCase;

class DateTimeHelperTest extends TestCase
{
    /**
     * @covers \App\Helpers\DateTimeHelper::getDate
     */
    public function testGetDate()
    {
        $dateTimer = new DateTimeHelper();
        $date = $dateTimer->getDate();
        $this->assertInstanceOf(\DateTime::class, $date);
    }

    /**
     * @covers \App\Helpers\DateTimeHelper::toDate
     */
    public function testToDate()
    {
        $dateTimer = new DateTimeHelper();
        $date = $dateTimer->toDate(date('Y-m-d H:i:s'));
        $this->assertInstanceOf(\DateTime::class, $date);
    }

    /**
     * @covers \App\Helpers\DateTimeHelper::createDateFromFormat
     */
    public function testCreateDateFromFormat()
    {
        $dateTimer = new DateTimeHelper();
        $date = $dateTimer->createDateFromFormat('Y-m-d', date('Y-m-d H:i:s'));
        $this->assertInstanceOf(\DateTime::class, $date);
    }
    
    /**
     * @covers \App\Helpers\DateTimeHelper::dateDbFormat
     */
    public function testDateDbFormat()
    {
        $dateTimer = new DateTimeHelper();
        $date = $dateTimer->dateDbFormat(date('Y-m-d H:i:s'));
        $this->assertInternalType('string', $date);
    }

    /**
     * @covers \App\Helpers\DateTimeHelper::dateBrFormat
     */
    public function testDateBrFormat()
    {
        $dateTimer = new DateTimeHelper();
        $date = $dateTimer->dateBrFormat(date('Y-m-d H:i:s'));
        $this->assertInternalType('string', $date);
    }

     /**
     * @covers \App\Helpers\DateTimeHelper::getTime
     */
    public function testGetTime()
    {
        $dateTimer = new DateTimeHelper();
        $date = $dateTimer->getTime(date('Y-m-d H:i:s'));
        $this->assertInternalType('string', $date);
    }

    /**
     * @covers \App\Helpers\DateTimeHelper::toString
     */
    public function testToString()
    {
        $dateTimer = new DateTimeHelper();
        $date = new \DateTime();
        $date = $dateTimer->toString($date);
        $this->assertInternalType('string', $date);
    }

    /**
     * @covers \App\Helpers\DateTimeHelper::onValidity
     */
    public function testOnValidity()
    {
        $now = date('Y-m-d H:i:s');
        $validDate = date('Y-m-d H:i:s', strtotime('+2 minutes', strtotime($now)));
        $dateTimer = new DateTimeHelper();
        $onValidity = $dateTimer->onValidity($validDate);
        $this->assertTrue($onValidity);
    }

    /**
     * @covers \App\Helpers\DateTimeHelper::onValidity
     */
    public function testOnValidityFalse()
    {
        $now = date('Y-m-d H:i:s');
        $invalidDate = date('Y-m-d H:i:s', strtotime('-1 minute', strtotime($now)));
        $dateTimer = new DateTimeHelper();
        $onValidity = $dateTimer->onValidity($invalidDate);
        $this->assertFalse($onValidity);
    }

    /**
     * @covers \App\Helpers\DateTimeHelper::dateAddMinutes
     */
    public function testDateAddMinutes()
    {
        $now = date('Y-m-d H:i:s');
        $dateAdd = date('Y-m-d H:i:s', strtotime('+15 minutes', strtotime($now)));
        $dateTimer = new DateTimeHelper();
        $getDate = $dateTimer->dateAddMinutes(15);
        $newDate = $getDate->format('Y-m-d H:i:s');
        $this->assertEquals($dateAdd, $newDate);
    }

    /**
     * @covers \App\Helpers\DateTimeHelper::dateAddHours
     */
    public function testDateAddHours()
    {
        $now = date('Y-m-d H:i:s');
        $dateAdd = date('Y-m-d H:i:s', strtotime('+2 hours', strtotime($now)));
        $dateTimer = new DateTimeHelper();
        $getDate = $dateTimer->dateAddHours(2);
        $newDate = $getDate->format('Y-m-d H:i:s');
        $this->assertEquals($dateAdd, $newDate);
    }

    /**
     * @covers \App\Helpers\DateTimeHelper::dateSubMinutes
     */
    public function testDateSubMinutes()
    {
        $now = date('Y-m-d H:i:s');
        $dateSub = date('Y-m-d H:i:s', strtotime('-15 minutes', strtotime($now)));
        $dateTimer = new DateTimeHelper();
        $getDate = $dateTimer->dateSubMinutes(15);
        $newDate = $getDate->format('Y-m-d H:i:s');
        $this->assertEquals($dateSub, $newDate);
    }

    /**
     * @covers \App\Helpers\DateTimeHelper::dateSubHours
     */
    public function testDateSubHours()
    {
        $now = date('Y-m-d H:i:s');
        $dateSub = date('Y-m-d H:i', strtotime('-1 hour', strtotime($now)));
        $dateTimer = new DateTimeHelper();
        $getDate = $dateTimer->dateSubHours(1);
        $newDate = $getDate->format('Y-m-d H:i');
        $this->assertEquals($dateSub, $newDate);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
