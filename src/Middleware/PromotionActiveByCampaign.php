<?php namespace App\Http\Middleware;
use App\Helpers\GTM;
use Closure;
use Genetsis\Promotions\Repositories\PromotionRepository;


class PromotionActiveByCampaign
{

    /**
     * add Middleware to inject promo active by campaign
     * Add to route middleware('promotion.active:campaing-key-sample')
     *
     * @param $request
     * @param Closure $next
     * @param $campaign
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     *
     */
    public function handle($request, Closure $next, $campaign)
    {
        try {
            $promotionRepository = new PromotionRepository();
            $promo_active = $promotionRepository->getPromotionActiveByCampaignKey($campaign);

            $request->attributes->add(['promotion_active' => $promo_active]);
            GTM::sendViewPage($promo_active->entry_point);

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return redirect(route('not-active'));
        }

        return $next($request);
    }
}
