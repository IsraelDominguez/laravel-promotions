<?php namespace Genetsis\Promotions\Contracts;

interface AfterFilterParticipationInterface {

    public function after(PromotionParticipationInterface $participation);
}