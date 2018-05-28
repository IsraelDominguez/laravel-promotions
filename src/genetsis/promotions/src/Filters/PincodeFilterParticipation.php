<?php namespace Genetsis\Promotions\Filters;

use Genetsis\Promotions\Contracts\BeforeFilterParticipationInterface;
use Genetsis\Promotions\Contracts\FilterParticipationInterface;
use Genetsis\Promotions\Contracts\PromotionParticipationInterface;
use Genetsis\Promotions\Exceptions\PromotionException;

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

            $this->promotion_service->getPincodeByCode($participation->getPincode());

        } catch (\InvalidArgumentException $e) {
            throw new PromotionException($e->getMessage());
        } catch (ModelNotFoundException $e) {
            throw new PromotionException('Error in Pincode - invalid, used or expired');
        } catch (PromotionException $e) {
            throw $e;
        }
    }
}