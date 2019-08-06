<?php namespace Genetsis\Promotions\Repositories;

use Genetsis\Promotions\Contracts\PromotionParticipationInterface;
use Genetsis\Promotions\Exceptions\PromotionNotActiveException;
use Genetsis\Promotions\Models\Campaign;
use Genetsis\Promotions\Models\Participation;
use Genetsis\Promotions\Models\Promotion;
use Genetsis\Promotions\Services\PromotionService;

class PromotionRepository {

    public function checkUserHasExtraParticipations($user_id, Promotion $promotion) {
        if (ExtraParticipation::where('promo_id', $promotion->id)->where('user_id', $user_id)->where('used', null)->count()>0) {
            return true;
        }
        return false;
    }

    public function getParticipationsByExtraField($promo_id, $key, $value = '') {

        return Participation::whereHas('extraFields', function ($query) use ($key, $value) {
            $query->where('key', $key);
            if ($value)
                $query->where('value', $value);
        })->where('promo_id', $promo_id)->get();
    }

    public function getPromotionActiveByCampaignKey($campaign_key) {
        try {
            $campaign = Campaign::where('key', $campaign_key)->firstOrFail();
            return $this->getPromotionActiveByCampagin($campaign);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getPromotionActiveByCampaignId($campaign_id) {
        try {
            $campaign = Campaign::findOrFail($campaign_id);
            return $this->getPromotionActiveByCampagin($campaign);
        } catch (\Exception $e) {
            throw $e;
        }
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

    public function getPromotionActiveByKey($promotion_key) {
        try {
            return Promotion::where('starts','<=',now())->where('ends','>=',now())->where('key', $promotion_key)->firstOrFail();
        } catch (\Exception $e) {
            throw $e;
        }
    }


    private function getPromotionActiveByCampagin(Campaign $campagin) {
        try {
            return $campagin->promotions()->where('starts','<=',now())->where('ends','>=',now())->firstOrFail();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
