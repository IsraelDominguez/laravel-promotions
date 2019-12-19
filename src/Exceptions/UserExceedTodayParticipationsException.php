<?php namespace Genetsis\Promotions\Exceptions;

class UserExceedTodayParticipationsException extends UserPromotionException
{
    public function __construct($exception)
    {
        parent::__construct($exception);
    }
}
