<?php

namespace App\Refiners;

use \Mockery;
use App\Refiners\Base\StdClassRefiner;
use App\Refiners\Base\CanTestRefine;
use PHPUnit\Framework\TestCase;

class StdClassRefinerTest extends TestCase
{
    /**
     * @covers \App\Refiners\Base\StdClassRefiner::refine
     * @covers \App\Refiners\Base\StdClassRefiner::refinerRow
     * @covers \App\Refiners\Base\StdClassRefiner::refinerData
     */
    public function testArrayRefiner()
    {
        $data = [
            'test_id' => 1,
            'test_name' => 'Description2',
        ];
            
        $canTestRefine = new CanTestRefine();

        $result = $canTestRefine->refine($data);
        
        $this->assertInstanceOf(CanTestRefine::class, $canTestRefine);
        $this->assertInternalType('array', $result);
    }

    /**
     * @covers \App\Refiners\Base\StdClassRefiner::params
     * @covers \App\Refiners\Base\StdClassRefiner::refinerParams
     * @covers \App\Refiners\Base\StdClassRefiner::paramsRow
     */
    public function testArrayRefinerParams()
    {
        $data = [
            'test_id' => 1,
            'test_name' => 'Description2',
        ];
            
        $canTestRefine = new CanTestRefine();

        $result = $canTestRefine->params($data);
        
        $this->assertInstanceOf(CanTestRefine::class, $canTestRefine);
        $this->assertInternalType('array', $result);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
