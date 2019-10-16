<?php namespace Genetsis\Promotions\Repositories;

use Genetsis\Promotions\Contracts\PromotionRepositoryInterface;
use Genetsis\Promotions\Contracts\PromotionTypeInterface;
use Illuminate\Http\Request;

class SorteoPromotionType extends GenericPromotion implements PromotionRepositoryInterface {

    public function __construct() {
    }

    public function save(Request $request) {
        parent::save($request);
    }

}
