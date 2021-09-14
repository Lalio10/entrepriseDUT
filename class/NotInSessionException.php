<?php


class NotInSessionException extends Exception
{


    /**
     * NotInSessionException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message, int $code = 0)
    {
        parent::__construct($message, $code);
    }
}