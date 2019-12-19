<?php namespace Genetsis\Promotions\Services;

use Carbon\Carbon;
use Genetsis\Promotions\Contracts\PromotionParticipationInterface;
use Genetsis\Promotions\Models\ExtraParticipation;
use Genetsis\Promotions\Models\Promotion;
use Illuminate\Support\Facades\Log;

class ExtraParticipationService
{
    protected $tz;

    public function __construct() {
        $this->tz = new \DateTimeZone(config('promotion.timezone', config('app.timezone')));
    }

    /**
     * Add User extra participation in a promotion
     *
     * @param $user_id
     * @param PromotionParticipationInterface $promotion
     * @throws \Exception
     */
    public function addUserExtraParticipation($user_id, PromotionParticipationInterface $promotion) {
        try {
            $extra_participation = new ExtraParticipation();
            $extra_participation->promo_id = $promotion->promo->id;
            $extra_participation->user_id = $user_id;
            $extra_participation->save();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }


    /**
     * Consume an user extra participation after participation
     *
     * @param PromotionParticipationInterface $participation
     * @throws \Exception
     */
    public function consumeUserExtraParticipation(PromotionParticipationInterface $participation) {
        try {
            //TODO: check if user need consume an extra participations
            if ($participation->promo->max_user_participations >= 0) {
                Log::debug('Consume Extra Participations after participate');
                if ($this->userCountExtraParticipations($participation->user_id, $participation->promo) >= $participation->promo->max_user_participations) {
                    if ($extra_participation = ExtraParticipation::where('promo_id', $participation->promo->id)->where('user_id', $participation->user_id)->where('used', null)->first()) {
                        $extra_participation->used = Carbon::now($this->tz);
                        $extra_participation->save();
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }

    /**
     * Return number of User Extra Participations in a promotion
     *
     * @param $user_id
     * @param \Genetsis\Promotions\Models\Promotion $promotion
     * @return int
     */
    public function userCountExtraParticipations($user_id, Promotion $promotion) {
        return ExtraParticipation::where('promo_id', $promotion->id)->where('user_id', $user_id)->where('used', null)->count();
    }

}
