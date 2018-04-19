<?php namespace Genetsis\Promotions\Events;


class PromoUserCreated
{

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }
}
