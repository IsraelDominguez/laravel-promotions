<?php namespace Genetsis\Promotions\Exceptions;

class InvalidPincodeException extends PromotionException
{
    public function __construct($exception)
    {
        parent::__construct($exception);
    }
}
