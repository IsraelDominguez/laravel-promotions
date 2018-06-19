<?php namespace Genetsis\Promotions\Services;

use Genetsis\Promotions\Contracts\PromotionParticipationInterface;
use Genetsis\Promotions\Models\ExtraFields;
use Genetsis\Promotions\Models\ExtraFieldsParticipations;
use Genetsis\Promotions\Models\ExtraParticipation;
use Illuminate\Support\Facades\Log;

class ExtraFieldsParticipationService
{
    public function __construct() {
    }

    /**
     * Add User extra Fields values participation in a promotion
     *
     * @param PromotionParticipationInterface $promotion
     * @throws \Exception
     */
    public function addExtraFieldsParticipation(PromotionParticipationInterface $promotion) {
        try {
            foreach ($promotion->getExtraFields() as $key => $value) {
                $extra_fields_participation = new ExtraFieldsParticipations();
                $extra_fields_participation->key = $key;
                $extra_fields_participation->participation_id = $promotion->id;
                $extra_fields_participation->value = $value;
                $extra_fields_participation->save();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }
}