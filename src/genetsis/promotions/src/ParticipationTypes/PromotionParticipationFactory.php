<?php namespace Genetsis\Promotions\ParticipationTypes;

use Genetsis\Promotions\Contracts\FilterParticipationInterface;
use Genetsis\Promotions\Contracts\PromotionParticipationInterface;
use Genetsis\Promotions\Models\Promotion;

class PromotionParticipationFactory
{

    /**
     * @param $promo_id
     * @return PromotionParticipationInterface
     * @throws \Exception
     */
    public static function create($promo_id)
    {
        try {
            $promotion = Promotion::findOrFail($promo_id);
            $class = __NAMESPACE__.'\Participation'.str_replace(' ', '', $promotion->type->name);;

            if (!class_exists($class)) {
                throw new \Exception('Promotion Type Not Defined: '.$class);
            }

            $temp = class_implements($class);
            if (!is_array($temp) || !in_array(PromotionParticipationInterface::class, $temp)) {
                throw new \Exception('Promotion Type Not Implement correct Interface: '.$class);
            }

            \App::when($class)->needs(FilterParticipationInterface::class)->give(\Genetsis\Promotions\Services\GenericFilterParticipationService::class);
            return \App::make($class)->setPromoId($promotion->id);
        } catch (ModelNotFoundException $e) {
            throw new PromotionException("Promotion Not Found: " . $promo_id);
        }
    }

}