<?php namespace Genetsis\Promotions\Exceptions;

class PincodeExpiredException extends InvalidPincodeException
{
    public function __construct($exception)
    {
        parent::__construct($exception);
    }
}
