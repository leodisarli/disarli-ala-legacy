<?php

namespace App\Helpers;

use \Mockery;
use PHPUnit\Framework\TestCase;

class ExportCsvHelperTest extends TestCase
{
    /**
     * @covers \App\Helpers\ExportCsvHelper::generateCsvFile
     */
    public function testGenerateCsvFile()
    {
        $data = [
            [
                'field' => 'data',
            ]
        ];
        $clientId = '1';

        $helper = new ExportCsvHelper();
        $file = $helper->generateCsvFile($data, $clientId);
        unlink($file);

        $this->assertInternalType('string', $file);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
