<?php namespace Genetsis\Promotions\Exceptions;

class PromotionNotActiveException extends PromotionException
{
    public function __construct($exception)
    {
        parent::__construct($exception);
    }
}
