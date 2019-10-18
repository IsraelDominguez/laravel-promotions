<?php namespace Genetsis\Promotions\Middleware;

use Closure;
use Genetsis\Promotions\Exceptions\PromotionNotActiveException;
use Genetsis\Promotions\Repositories\PromotionRepository;
use Genetsis\Promotions\Services\PromotionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PromotionActive
{
    /**
     * @var PromotionRepository $promotion_repository
     */
    protected $promotion_repository;

    public function __construct(PromotionRepository $promotionRepository)
    {
        $this->promotion_repository = $promotionRepository;
    }

    /**
     * Check if promo exist and is active
     *
     * @param $request
     * @param Closure $next
     * @param $promotion key promotion Optional
     * @return mixed (404|500|route('not-active')
     *  404 eror if not exist
     *  500 if server error
     *  'not-active' named route if is not active
     */
    public function handle($request, Closure $next, $promotion = null)
    {
        try {
            if (($request->promokey)||!empty($promotion)) {
                $promotion_active = $this->promotion_repository->getPromotionByKey($request->promokey ?? $promotion);

                $request->attributes->add(['promotion_active' => $promotion_active]);

                return $next($request);
            } else {
                throw new PromotionNotActiveException('Not Data');
            }
        } catch (PromotionNotActiveException $e) {
            \Log::info('Promotion not Active');
            return redirect(route('not-active', $request->promokey ?? $promotion ?? ''));
        } catch (ModelNotFoundException $e) {
            \Log::error($e->getMessage());
            abort(404);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            abort(500);
        }
    }
}
