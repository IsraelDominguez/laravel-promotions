<?php namespace Genetsis\Promotions\Services;

use Genetsis\Promotions\Contracts\PromotionParticipationInterface;
use Genetsis\Promotions\Models\ExtraFields;
use Genetsis\Promotions\Models\ExtraFieldsParticipations;
use Genetsis\Promotions\Models\ExtraParticipation;
use Genetsis\Promotions\Models\RewardsParticipations;
use Illuminate\Support\Facades\Log;

class RewardsParticipationService
{
    public function __construct() {
    }

    /**
     * Add User extra Fields values participation in a promotion
     *
     * @param PromotionParticipationInterface $promotion
     * @throws \Exception
     */
    public function addRewardsParticipation(PromotionParticipationInterface $promotion) {
        try {
            foreach ($promotion->getRewards() as $key => $amount) {
                $reward_participation = new RewardsParticipations();
                $reward_participation->key = $key;
                $reward_participation->participation_id = $promotion->id;
                $reward_participation->amount = $amount;
                $reward_participation->save();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }
}