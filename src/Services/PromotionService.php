<?php namespace Genetsis\Promotions\Services;

use Carbon\Carbon;
use Genetsis\Promotions\Exceptions\PromotionException;
use Genetsis\Promotions\Exceptions\UserExceedCampaignParticipationsException;
use Genetsis\Promotions\Exceptions\UserExceedParticipationsException;
use Genetsis\Promotions\Exceptions\UserExceedTodayParticipationsException;
use Genetsis\Promotions\Models\Promotion;
use Genetsis\Promotions\Models\Participation;

class PromotionService
{
    protected $extra_participations;

    protected $tz;

    public function __construct(ExtraParticipationService $extra_participations_service) {
        $this->tz = new \DateTimeZone(config('promotion.timezone', config('app.timezone')));
        $this->extra_participations = $extra_participations_service;
    }

    /**
     * Check if a promotion is Active
     *
     * @return bool
     * @throws PromotionException
     */
    public function isActive(Promotion $promotion) {
        if (($promotion->starts != null) && ($promotion->ends != null)) {
            return $this->isStarted($promotion) && !$this->isFinished($promotion);
        } else {
            return $this->isStarted($promotion) || !$this->isFinished($promotion) ;
        }
    }

    /**
     * Check is a Promotion is started
     *
     * @param \Genetsis\Promotions\Models\Promotion $promotion
     * @return bool
     */
    public function isStarted(Promotion $promotion) {
        return ($promotion->starts != null)
            ? !Carbon::now($this->tz)->lessThan(Carbon::createFromFormat('Y-m-d H:i:s', $promotion->starts, $this->tz))
            : true;
    }

    /**
     * Check if a Promotion is finished
     *
     * @param \Genetsis\Promotions\Models\Promotion $promotion
     * @return bool
     */
    public function isFinished(Promotion $promotion) {
        return ($promotion->ends != null)
            ? Carbon::now($this->tz)->greaterThan(Carbon::createFromFormat('Y-m-d H:i:s',$promotion->ends, $this->tz))
            : false;
    }

    /**
     * Check if an User can participate in a Promotion
     *
     * @param $user_id
     * @param \Genetsis\Promotions\Models\Promotion $promotion
     * @return bool
     * @throws UserExceedParticipationsException
     * @throws UserExceedTodayParticipationsException
     * @throws UserExceedCampaignParticipationsException
     */
    public function userCanParticipate(string $user_id, Promotion $promotion) {

        if ($promotion->campaign->max_user_participations) {
            if ($promotion->campaign->max_user_participations > 0) {

                if (Participation::where('user_id', $user_id)
                        ->whereHas('promo', function (\Illuminate\Database\Eloquent\Builder $query) use ($promotion) {
                            $query->where('campaign_id', $promotion->campaign_id);
                        })->count()
                     >= $promotion->campaign->max_user_participations)
                {
                    throw new UserExceedCampaignParticipationsException("User Campaign Participation Exceed");
                }
            }
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
}
