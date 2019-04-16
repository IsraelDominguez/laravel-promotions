<?php namespace Genetsis\Promotions\Filters;

use Carbon\Carbon;
use Genetsis\Promotions\Contracts\BeforeFilterParticipationInterface;
use Genetsis\Promotions\Contracts\FilterParticipationInterface;
use Genetsis\Promotions\Contracts\PromotionParticipationInterface;
use Genetsis\Promotions\Exceptions\InvalidPincodeException;
use Genetsis\Promotions\Exceptions\PromotionException;

use Genetsis\Promotions\Models\Codes;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webmozart\Assert\Assert;

class PincodeFilterParticipation extends GenericFilterParticipation implements FilterParticipationInterface, BeforeFilterParticipationInterface {

    public function __construct() {
        parent::__construct();
    }

    public function before(PromotionParticipationInterface $participation) {
        try {
            parent::before($participation);

            \Log::debug('Before Pincode Filter');

            Assert::notNull($participation->getPincode(), 'Pincode is required');
            Assert::notEmpty($participation->getPincode(), 'Pincode is required');

            Codes::where('code', $participation->getPincode())
                    ->where('used', null)
                    ->where('promo_id', $participation->promo)
                    ->where(function($q) {
                        $q->whereNull('expires')->orWhereDate('expires', '>=', Carbon::today()->toDateString());
                    })
                    ->firstOrFail();

        } catch (\InvalidArgumentException $e) {
            throw new PromotionException($e->getMessage());
        } catch (ModelNotFoundException $e) {
            throw new InvalidPincodeException('Error in Pincode - invalid, used or expired');
        } catch (PromotionException $e) {
            throw $e;
        }
    }
}