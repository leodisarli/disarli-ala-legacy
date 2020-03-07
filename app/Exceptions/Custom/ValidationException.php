<?php

namespace App\Exceptions\Custom;

class ValidationException extends \Exception
{
    private $header;
    
    /**
     * Constructor
     * @param array $error
     */
    public function __construct($error)
    {
        parent::__construct($error["message"], $error["code"]);
        $this->header = $error["header"];
    }

    /**
     * Generate string response
     * @return string
     */
    public function __toString()
    {
        return __class__ . ': [header:' . $this->header . ' | code:' . $this->code . ']: ' . $this->message . '\n';
    }

    public function getHeader()
    {
        return $this->header;
    }
}
