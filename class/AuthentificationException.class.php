<?php

require_once "autoload.inc.php";

class AuthentificationException extends Exception
{
    public function __construct(string $message = "L'identifiant ou le mot de passe est invalide", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}