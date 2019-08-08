<?php namespace Genetsis\Promotions\PromotionTypes;

use Genetsis\Promotions\Contracts\PromotionTypeInterface;
use Genetsis\Promotions\Models\Promotion;
use Illuminate\Http\Request;

class PincodesPromotionType extends GenericPromotion implements PromotionTypeInterface {

    public function __construct(Promotion $promotion) {
        parent::__construct($promotion);
    }

    public function save(Request $request) {
        parent::save($request);

        if ($request->hasFile('pincodes_file')) {
            if ($request->has('remove_prev')) {
                $this->promotion->codes()->delete();
            }

            $pincodes = \Genetsis\Promotions\Seeds\PromotionSeedsHelper::csvToArray($request->file('pincodes_file')->getPathname());
            foreach ($pincodes as $pincode) {
                $codes[] = [
                    'promo_id' => $this->promotion->id,
                    'code' => $pincode[0],
                    'win_code' => $pincode[1],
                    'expires' => $pincode[2]
                ];
            }
            if (!empty($codes)) {
                \Illuminate\Support\Facades\DB::table('promo_codes')->insert($codes);
            }
        }
    }


}
