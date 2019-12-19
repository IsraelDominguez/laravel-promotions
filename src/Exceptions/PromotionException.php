<?php namespace Genetsis\Promotions\Exceptions;

class PromotionException extends \Exception
{
    public function __construct($exception)
    {
        parent::__construct($exception);
    }
}
