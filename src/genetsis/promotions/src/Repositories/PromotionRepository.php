<?php namespace Genetsis\Promotions\Repositories;

use Genetsis\Promotions\Contracts\PromotionParticipationInterface;
use Genetsis\Promotions\Models\Participation;
use Genetsis\Promotions\Models\Promotion;

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

    public function getPromotionActiveByCampaign($campaign_id) {

        return Campaign::findOrFail($campaign_id)->promotions()->where('starts','<=',new Carbon('now'))->where('ends','>=',new Carbon('now'))->firstOrFail();
    }
}