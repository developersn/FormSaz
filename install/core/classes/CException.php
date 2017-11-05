<?php
class CException extends Exception
{
    
    public function __construct($message, $code = 0, Exception $previous = null) 
	{
		die($message);
        //parent::__construct($message, $code, $previous);
    }

    public function __toString() 
	{
        //return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
		return $this->message;
    }

}

