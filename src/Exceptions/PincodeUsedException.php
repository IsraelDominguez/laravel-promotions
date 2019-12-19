<?php namespace Genetsis\Promotions\Exceptions;

class PincodeUsedException extends InvalidPincodeException
{
    public function __construct($exception)
    {
        parent::__construct($exception);
    }
}
