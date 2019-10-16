<?php namespace Genetsis\Promotions\Repositories;

use Genetsis\Promotions\Contracts\PromotionRepositoryInterface;
use Genetsis\Promotions\Models\Moment;
use Genetsis\Promotions\Models\Promotion;
use Illuminate\Http\Request;

class WinMomentPromotionType extends GenericPromotion implements PromotionRepositoryInterface {

    public function __construct(Promotion $promotion) {
        parent::__construct($promotion);
    }

    public function save(Request $request) {
        parent::save($request);

        if ($request->hasFile('win_moment_file')) {
            if ($request->has('remove_prev')) {
                $this->promotion->moment()->delete();
            }

            $promo_id = $this->promotion->id;
            collect(\Genetsis\Promotions\Seeds\PromotionSeedsHelper::csvToArray($request->file('win_moment_file')->getPathname()))->filter(function($item) {
                return !empty($item['moment']);
            })->map(function($item, $key) use ($promo_id) {
                return new Moment([
                    'promo_id' => $promo_id,
                    'date' => trim($item['moment']),
                    'code_to_send' => $item['code'] ? trim($item['code']) : null
                ]);
            })->each(function ($item) {
                $item->save();
            });
        }
    }


}
