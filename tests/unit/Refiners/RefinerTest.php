<?php

namespace App\Refiners;

use \Mockery;
use App\Refiners\Refiner;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class RefinerTest extends TestCase
{
    /**
     * @covers \App\Refiners\Refiner::refine
     */
    public function testRefiner()
    {
        $data = [];
        $nameRefiner = 'App\Refiners\Concrete\Sample\SampleActionRefiner';

        $refiner = new Refiner();
        $return = $refiner->refine($nameRefiner, $data);
        
        $this->assertInstanceOf(Refiner::class, $refiner);
        $this->assertInternalType('array', $return);
    }

    /**
     * @covers \App\Refiners\Refiner::params
     */
    public function testParams()
    {
        $data = [];
        $nameRefiner = 'App\Refiners\Concrete\Sample\SampleActionRefiner';

        $refiner = new Refiner();
        $return = $refiner->params($nameRefiner, $data);
        
        $this->assertInstanceOf(Refiner::class, $refiner);
        $this->assertInternalType('array', $return);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
