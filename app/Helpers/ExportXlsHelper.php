<?php

namespace App\Helpers;

class ExportXlsHelper
{
    private $bodyArray;
    private $filename;
    private $headerArray;
    private $rowNo = 0;

    /**
     * Set name of the File
     * @param string $filename name of the file
     */
    private function setFilename(
        string $filename
    ) {
        $this->filename = $filename;
    }

    /**
     * Add header in xls file
     * @param $header header of text
     */
    private function addHeader(
        $header
    ) {
        if (is_array($header)) {
            $this->headerArray[] = $header;
            return;
        }
        $this->headerArray[][0] = $header;
    }

    /**
     * Add row in xls file
     * @param $row row of text
     */
    private function addRow(
        $row
    ) {
        if (is_array($row) && count($row) > 0) {
            if (isset($row[0]) && is_array($row[0])) {
                foreach ($row as $array) {
                    $this->bodyArray[] = $array;
                }
            }
        }
    }

    /**
     * Generate file
     */
    private function sendFile()
    {
        $xls = $this->buildXls();

        $file = fopen($this->filename, 'a');
        fwrite($file, $xls);
        fclose($file);
    }

    /**
     * Build XLS file
     */
    private function buildXls()
    {
        $xls = pack('ssssss', 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
        if (is_array($this->headerArray)) {
            $xls .= $this->build($this->headerArray);
        }
        if (is_array($this->bodyArray)) {
            $xls .= $this->build($this->bodyArray);
        }
        $xls .= pack('ss', 0x0A, 0x00);
        return $xls;
    }

    private function numericOrText(
        $field,
        $colNo
    ) {
        if (is_numeric($field)) {
            return $this->numFormat($this->rowNo, $colNo, $field);
        }
        return $this->textFormat($this->rowNo, $colNo, $field);
    }

    /**
     * Build file
     */
    private function build(
        $array
    ) {
        $build = '';
        foreach ($array as $row) {
            $colNo = 0;
            foreach ($row as $field) {
                $build .= $this->numericOrText($field, $colNo);
                $colNo++;
            }
            $this->rowNo++;
        }
        return $build;
    }

    /**
     * Format text
     */
    private function textFormat(
        $row,
        $col,
        $data = []
    ) {
        $data = utf8_decode($data);
        $length = strlen($data);
        $field = pack('ssssss', 0x204, 8 + $length, $row, $col, 0x0, $length);
        $field .= $data;
        return $field;
    }
    
    /**
     * Format numbers
     */
    private function numFormat(
        $row,
        $col,
        $data
    ) {
        $field = pack('sssss', 0x203, 14, $row, $col, 0x0);
        $field .= pack('d', $data);
        return $field;
    }

    /**
     * Generate XLS File
     * @param array $data array with the report data
     * @param string $clientId id of the client
     * @return string
     */
    public function generateXlsFile(
        $data,
        $clientId
    ) {
        $baseUrl = storage_path().'/report/';
        $fileName = $baseUrl . date('dmYHis'). '-' . $clientId . '.xls';
        $this->setFilename($fileName);
        $header = 'Relatório gerado em: ' . date('d-m-Y H:i:s');
        $this->addHeader($header);
        $this->addHeader(null);
        $header = [];
        if (count($data) > 0) {
            foreach ($data[0] as $key => $value) {
                $header[] = $key;
            }
            $this->addHeader($header);
            $row = [];
            foreach ($data as $value) {
                $row[] = $value;
            }
            $row = [];
            foreach ($data as $value) {
                $row[] = $value;
            }
            $this->addRow($row);
        }
        $this->sendFile();
        return $fileName;
    }
}
