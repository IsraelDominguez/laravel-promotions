<?php namespace Genetsis\Promotions\Exceptions;

class UserExceedParticipationsException extends UserPromotionException
{
    public function __construct($exception)
    {
        parent::__construct(" $exception ");
    }
}