<?php namespace Genetsis\Promotions\Repositories;

use Genetsis\Promotions\Contracts\PromotionRepositoryInterface;
use Genetsis\Promotions\Contracts\PromotionTypeInterface;
use Genetsis\Promotions\Models\Codes;
use Genetsis\Promotions\Models\Promotion;
use Illuminate\Http\Request;

class PincodePromotionType extends GenericPromotion implements PromotionRepositoryInterface {

    public function __construct(Promotion $promotion) {
        parent::__construct($promotion);
    }

    public function save(Request $request) {
        parent::save($request);

        if ($request->hasFile('pincodes_file')) {
            if ($request->has('remove_prev')) {
                $this->promotion->codes()->delete();
            }

            $promo_id = $this->promotion->id;
            collect(\Genetsis\Promotions\Seeds\PromotionSeedsHelper::csvToArray($request->file('pincodes_file')->getPathname()))->filter(function($item) {
                return !empty($item['code']);
            })->map(function($item, $key) use ($promo_id) {
                return new Codes([
                    'promo_id' => $promo_id,
                    'code' => trim($item['code']),
                    'win_code' => $item['win_code'] ? trim($item['win_code']) : null,
                    'expires' => $item['expires'] ? trim($item['expires']) : null
                ]);
            })->each(function ($item) {
                $item->save();
            });
        }
    }


}
