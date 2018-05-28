<?php namespace Genetsis\Promotions\Filters;

use Genetsis\Promotions\Contracts\AfterFilterParticipationInterface;
use Genetsis\Promotions\Contracts\BeforeFilterParticipationInterface;
use Genetsis\Promotions\Contracts\FilterParticipationInterface;
use Genetsis\Promotions\Contracts\PromotionParticipationInterface;
use Genetsis\Promotions\Exceptions\PromotionException;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webmozart\Assert\Assert;

class PincodeFilterParticipation extends GenericFilterParticipation implements FilterParticipationInterface, AfterFilterParticipationInterface, BeforeFilterParticipationInterface {


    public function __construct() {
        parent::__construct();
    }

    public function after(PromotionParticipationInterface $participation) {

        parent::after($participation);

        \Log::debug('After Pincode Filter');

    }

    public function before(PromotionParticipationInterface $participation) {
        try {
            parent::before($participation);

            \Log::debug('Before Pincode Filter');

            //TODO: check exist and not used pincode
            \Log::debug('Check Valid Pincode');

        } catch (PromotionException $e) {
            throw $e;
        }
    }
}