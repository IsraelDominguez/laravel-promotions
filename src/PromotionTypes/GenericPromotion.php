<?php namespace Genetsis\Promotions\PromotionTypes;

use Genetsis\Promotions\Contracts\PromotionTypeInterface;
use Genetsis\Promotions\Models\ExtraFields;
use Genetsis\Promotions\Models\Promotion;
use Genetsis\Promotions\Models\Rewards;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GenericPromotion implements PromotionTypeInterface
{
    /* @var Promotion $promotion
     */
    protected $promotion;

    public function __construct() {
    }

    public function setPromotion(Promotion $promotion) {
        $this->promotion = $promotion;
        return $this;
    }

    public function save(Request $request)
    {
        if (config('promotion.front_templates_enabled'))
            $this->saveSeo($request);

        if (config('promotion.extra_fields_enabled'))
            $this->saveExtraFields($request);

        if (config('promotion.rewards_fields_enabled'))
            $this->saveRewards($request);
    }

    /**
     * @param Request $request
     * @param $extra_field
     */
    private function saveRewards(Request $request): void
    {
        if ($rewards_keys = $request->get('reward_keys')) {
            foreach ($this->promotion->rewards as $reward) {
                if (!in_array($reward->key, $rewards_keys)) {
                    Log::debug("Elimino reward field: " . $reward->key);
                    Rewards::destroy($reward->key);
                }
            }

            foreach ($rewards_keys as $key => $reward) {
                if ($reward != null) {
                    if ($this->promotion->rewards->contains('key', $reward)) {
                        Log::debug("Edit reward: " . $reward);
                        Rewards::where('key', $reward)
                            ->update(['name' => $request->get('reward_names')[$key], 'stock' => ($request->get('reward_stocks')[$key]) ?: 0]);
                    } else {
                        Log::debug("New reward: " . $reward);
                        $rewardField = new Rewards();
                        $rewardField->key = $reward;
                        $rewardField->name = $request->get('reward_names')[$key];
                        $rewardField->stock = ($request->get('reward_stocks')[$key]) ?: 0;
                        $rewardField->promo_id = $this->promotion->id;
                        $rewardField->save();
                    }
                }
            }
        }
    }

    /**
     * @param Request $request
     */
    private function saveExtraFields(Request $request): void
    {
        if ($extra_fields_keys = $request->get('extra_field_keys')) {
            foreach ($this->promotion->extra_fields as $extra_field) {
                if (!in_array($extra_field->key, $extra_fields_keys)) {
                    Log::debug("Elimino extra field: " . $extra_field->key);
                    ExtraFields::destroy($extra_field->key);
                }
            }

            foreach ($extra_fields_keys as $key => $extra_field) {
                if ($extra_field != null) {
                    if ($this->promotion->extra_fields->contains('key', $extra_field)) {
                        Log::debug("Edit extra field: " . $extra_field);
                        ExtraFields::where('key', $extra_field)
                            ->update([
                                'name' => $request->get('extra_field_names')[$key],
                                'type' => $request->get('extra_field_types')[$key]
                            ]);
                    } else {
                        Log::debug("New extra field: " . $extra_field);

                        $extraField = new ExtraFields();
                        $extraField->key = $extra_field;
                        $extraField->name = $request->get('extra_field_names')[$key];
                        $extraField->type = $request->get('extra_field_types')[$key];
                        $extraField->promo_id = $this->promotion->id;

                        Log::debug("Extra field: type" . $extraField->type);

                        $extraField->save();
                    }
                }
            }
        }
    }

    /**
     * @param Request $request
     */
    private function saveSeo(Request $request): void
    {
        $this->promotion->seo()->updateOrCreate([
            'promo_id' => $this->promotion->id
        ], [
            'title' => $request->input('title'),
            'facebook' => $request->input('facebook'),
            'twitter' => $request->input('twitter'),
            'whatsapp' => $request->input('whatsapp'),
        ]);
    }
}
