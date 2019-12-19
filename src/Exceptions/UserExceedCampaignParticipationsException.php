<?php namespace Genetsis\Promotions\Exceptions;

class UserExceedCampaignParticipationsException extends UserPromotionException
{
    public function __construct($exception)
    {
        parent::__construct(" $exception ");
    }
}
