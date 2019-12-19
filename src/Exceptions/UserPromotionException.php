<?php namespace Genetsis\Promotions\Exceptions;

class UserPromotionException extends PromotionException
{
    public function __construct($exception)
    {
        parent::__construct($exception);
    }
}
