<?php namespace Genetsis\Promotions\Contracts;

interface BeforeFilterParticipationInterface {

    public function before(PromotionParticipationInterface $participation);
}