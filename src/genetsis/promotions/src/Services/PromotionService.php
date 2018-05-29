<?php namespace Genetsis\Promotions\Services;

use Carbon\Carbon;
use Genetsis\Promotions\Exceptions\PromotionException;
use Genetsis\Promotions\Exceptions\UserExceedParticipationsException;
use Genetsis\Promotions\Exceptions\UserExceedTodayParticipationsException;
use Genetsis\Promotions\Exceptions\UserPromotionException;
use Genetsis\Promotions\Models\Codes;
use Genetsis\Promotions\Models\Participation;

class PromotionService
{

    protected $extra_participations;

    public function __construct(ExtraParticipationService $extra_participations_service) {
        $this->extra_participations = $extra_participations_service;
    }

    /**
     * Check if a promotion is Active
     *
     * @return bool
     * @throws PromotionException
     */
    public function isActive(\Genetsis\Promotions\Models\Promotion $promotion) {
        if (($promotion->starts != null)&&($promotion->ends != null)) {
            return Carbon::now()->between(Carbon::createFromFormat('Y-m-d H:i:s',$promotion->starts), Carbon::createFromFormat('Y-m-d H:i:s',$promotion->ends));
        } else {
            if ($promotion->ends != null)
                return $this->isFinished($promotion);

            if ($promotion->starts != null)
                return $this->isStarted($promotion);
        }
        return true;
    }

    /**
     * Check is a Promotion is started
     *
     * @param \Genetsis\Promotions\Models\Promotion $promotion
     * @return bool
     */
    public function isStarted(\Genetsis\Promotions\Models\Promotion $promotion) {
        if ($promotion->starts != null) {
            return !Carbon::now()->lessThan(Carbon::createFromFormat('Y-m-d H:i:s',$promotion->starts));
        }
        return true;
    }

    /**
     * Check if a Promotion is finished
     *
     * @param \Genetsis\Promotions\Models\Promotion $promotion
     * @return bool
     */
    public function isFinished(\Genetsis\Promotions\Models\Promotion $promotion) {
        if ($promotion->ends != null) {
            return !Carbon::now()->greaterThan(Carbon::createFromFormat('Y-m-d H:i:s',$promotion->ends));
        }
        return true;
    }

    /**
     * Check if an User can participate in a Promotion
     *
     * @param $user_id
     * @param \Genetsis\Promotions\Models\Promotion $promotion
     * @return bool
     * @throws UserExceedParticipationsException
     * @throws UserExceedTodayParticipationsException
     */
    public function userCanParticipate($user_id, \Genetsis\Promotions\Models\Promotion $promotion) {
        if ($user_id == null) {
            return false;
        }

        if ($promotion->max_user_participations != null) {
            if ($promotion->max_user_participations > 0) {
                if (Participation::where('promo_id', $promotion->id)->where('user_id', $user_id)->count() >= $promotion->max_user_participations) {
                    if (!$this->extra_participations->userCountExtraParticipations($user_id, $promotion) > 0) {
                        throw new UserExceedParticipationsException("User Participation Exceed");
                    }
                }
            } else {
                // If max_user_participations = 0 only can participate if user has extra participations
                if (!$this->extra_participations->userCountExtraParticipations($user_id, $promotion) > 0)
                    throw new UserExceedParticipationsException("User Participation Exceed");
            }
        }

        if ($promotion->max_user_participations_by_day > 0) {
            $today = Carbon::today();
            if (Participation::whereDate('date', $today)->where('promo_id', $promotion->id)->where('user_id', $user_id)->count() >= $promotion->max_user_participations_by_day) {
                throw new UserExceedTodayParticipationsException("Day Participation Exceed");
            }
        }
        return true;
    }

    public function getPincodeByCode($pincode, \Genetsis\Promotions\Models\Promotion $promotion) {

        return Codes::where('code', $pincode)
                ->where('used', null)
                ->where('promotion_id', $promotion->id)
                ->where(function($q) {
                    $q->whereNull('expires')->orWhereDate('expires', '>=', Carbon::today()->toDateString());
                })
                ->firstOrFail();
    }
}