<?php

namespace App\Helpers;

use \Mockery;
use PHPUnit\Framework\TestCase;

class ExportXlsHelperTest extends TestCase
{
    /**
     * @covers \App\Helpers\ExportXlsHelper::setFilename
     * @covers \App\Helpers\ExportXlsHelper::addHeader
     * @covers \App\Helpers\ExportXlsHelper::addRow
     * @covers \App\Helpers\ExportXlsHelper::sendFile
     * @covers \App\Helpers\ExportXlsHelper::buildXls
     * @covers \App\Helpers\ExportXlsHelper::build
     * @covers \App\Helpers\ExportXlsHelper::textFormat
     * @covers \App\Helpers\ExportXlsHelper::numFormat
     * @covers \App\Helpers\ExportXlsHelper::generateXlsFile
     * @covers \App\Helpers\ExportXlsHelper::numericOrText
     */
    public function testGenerateXlsFile()
    {
        $data = [
            [
                'field1' => 'data',
                'field2' => 1,
            ]
        ];
        $clientId = '1';

        $helper = new ExportXlsHelper();
        $file = $helper->generateXlsfile($data, $clientId);
        unlink($file);

        $this->assertInternalType('string', $file);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
