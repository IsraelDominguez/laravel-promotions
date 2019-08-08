<?php namespace Genetsis\Promotions\PromotionTypes;

use Genetsis\Promotions\Contracts\PromotionTypeInterface;
use Genetsis\Promotions\Models\Promotion;
use Illuminate\Http\Request;

class MomentPromotionType extends GenericPromotion implements PromotionTypeInterface {

    public function __construct(Promotion $promotion) {
        parent::__construct($promotion);
    }

    public function save(Request $request) {
        parent::save($request);

        if ($request->hasFile('win_moment_file')) {
            if ($request->has('remove_prev')) {
                $this->promotion->moment()->delete();
            }
            $moments = \Genetsis\Promotions\Seeds\PromotionSeedsHelper::csvToArray($request->file('win_moment_file')->getPathname());
            foreach ($moments as $moment) {
                $codes[] = [
                    'promo_id' => $this->promotion->id,
                    'code_to_send' => $moment[0],
                    'date' => $moment[1]
                ];
            }
            if (!empty($codes)) {
                \Illuminate\Support\Facades\DB::table('promo_moments')->insert($codes);
            }
        }
    }


}
