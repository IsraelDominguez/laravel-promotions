<?php namespace Genetsis\Promotions\Repositories;

use Genetsis\Promotions\Contracts\PromotionParticipationInterface;
use Genetsis\Promotions\Exceptions\PromotionNotActiveException;
use Genetsis\Promotions\Models\Campaign;
use Genetsis\Promotions\Models\Participation;
use Genetsis\Promotions\Models\Promotion;
use Genetsis\Promotions\Services\PromotionService;

class PromotionRepository {

    protected $tz;

    public function __construct()
    {
        $this->tz = new \DateTimeZone(config('promotion.timezone', config('app.timezone')));
    }

    /**
     * Check if user has Extra Participations
     * @param string $user_id
     * @param Promotion $promotion
     * @return bool
     */
    public function checkUserHasExtraParticipations(string $user_id, Promotion $promotion) {
        return ExtraParticipation::where('promo_id', $promotion->id)
                ->where('user_id', $user_id)
                ->where('used', null)
                ->count() > 0;
    }

    /**
     * Get Participations by Extra Field
     * @param string $promo_id
     * @param string $key
     * @param string $value
     * @return mixed
     */
    public function getParticipationsByExtraField(string $promo_id, string $key, $value = '') {

        return Participation::whereHas('extraFields', function ($query) use ($key, $value) {
            $query->where('key', $key);
            if ($value)
                $query->where('value', $value);
        })->where('promo_id', $promo_id)->get();
    }

    /**
     * Get First promotion by Campaign key
     *
     * @param $campaign_key
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getPromotionActiveByCampaignKey($campaign_key) {
        $campaign = Campaign::where('key', $campaign_key)->firstOrFail();

        return $this->getPromotionActiveByCampaign($campaign);
    }

    /**
     * Get Promotion by id
     *
     * @param $campaign_id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getPromotionActiveByCampaignId($campaign_id) {
        $campaign = Campaign::findOrFail($campaign_id);

        return $this->getPromotionActiveByCampaign($campaign);
    }

    /**
     * Get promotion by KEY
     *
     * @param string $promotion_key
     * @return Promotion
     * @throws \Exception
     */
    public function getPromotionByKey(string $promotion_key) {
        try {
            $promotion_service = \App::make(PromotionService::class);

            $promotion = Promotion::where('key', $promotion_key)->firstOrFail();

            if ($promotion_service->isActive($promotion)) {
                return $promotion;
            } else {
                throw new PromotionNotActiveException("Not Active");
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get promotion by key, check if active
     * @param $promotion_key
     * @return mixed
     */
    public function getPromotionActiveByKey($promotion_key) {
        return Promotion::where('key', $promotion_key)
            ->where('starts', '<=', now($this->tz))
            ->where('ends', '>=', now($this->tz))
            ->firstOrFail();
    }


    /**
     * Get First promotion active by Campaign
     * @param Campaign $campaign
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    private function getPromotionActiveByCampaign(Campaign $campaign) {

        return $campaign->promotions()
            ->where('starts', '<=', now($this->tz))
            ->where('ends', '>=', now($this->tz))
            ->firstOrFail();
    }


    /**
     * Get all Active Promotions
     *
     * @param Campaign $campaign
     * @return \Illuminate\Database\Eloquent\Collection $promotions
     * @throws \Exception
     */
    public function getPromotionsActiveByCampaign(Campaign $campaign) {

        return $campaign->promotions()
            ->where('starts', '<=', now($this->tz))
            ->where('ends', '>=', now($this->tz))
            ->get();
    }
}
