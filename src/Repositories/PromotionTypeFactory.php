<?php namespace Genetsis\Promotions\Repositories;

use Genetsis\Promotions\Contracts\PromotionRepositoryInterface;
use Genetsis\Promotions\Models\Promotion;

class PromotionTypeFactory
{
    /**
     * @param Promotion $promotion
     * @return PromotionTypeInterface
     * @throws \Exception
     */
    public static function create(Promotion $promotion)
    {
        $class = sprintf("%s\%sPromotionType", __NAMESPACE__, str_replace(' ','',$promotion->type->name));

        if (!class_exists($class)) {
            throw new \Exception('Promotion Type Not Defined: '.$class);
        }

        $temp = class_implements($class);
        if (!is_array($temp) || !in_array( PromotionRepositoryInterface::class, $temp)) {
            throw new \Exception('Promotion Type Not Implement correct Interface: '.$class);
        }

        return \App::make($class)->setPromotion($promotion);
    }

}
