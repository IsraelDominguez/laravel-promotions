<?php namespace Genetsis\Promotions\PromotionTypes;

use Genetsis\Promotions\Contracts\PromotionTypeInterface;
use Genetsis\Promotions\Models\Promotion;
use Illuminate\Http\Request;

class SorteoPromotionType extends GenericPromotion implements PromotionTypeInterface {

    public function __construct() {
    }

    public function save(Request $request) {
        parent::save($request);
    }

}
