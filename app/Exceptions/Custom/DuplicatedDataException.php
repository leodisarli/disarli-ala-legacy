<?php

namespace App\Exceptions\Custom;

class DuplicatedDataException extends \Exception
{
    private $messages;
    
    /**
     * Constructor
     * @param array $validationMessages
     */
    public function __construct(array $validationMessages)
    {
        $this->messages = $validationMessages;
    }
    
    /**
     * Get messages
     * @return array $this->messages
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
