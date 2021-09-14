<?php


class SessionException extends Exception
{
    public function __construct(string $message = "Erreur de session", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}