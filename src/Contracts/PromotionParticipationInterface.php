<?php namespace Genetsis\Promotions\Contracts;

use Genetsis\Promotions\ParticipationTypes\ParticipationResult;

interface PromotionParticipationInterface {

    /**
     * @return ParticipationResult
     */
    public function participate();
}