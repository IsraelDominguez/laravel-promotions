<?php namespace Genetsis\Promotions\Filters;

use Genetsis\Promotions\Contracts\BeforeFilterParticipationInterface;
use Genetsis\Promotions\Contracts\FilterParticipationInterface;
use Genetsis\Promotions\Contracts\PromotionParticipationInterface;
use Genetsis\Promotions\Exceptions\PromotionException;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webmozart\Assert\Assert;

class QrsFilterParticipation extends GenericFilterParticipation implements FilterParticipationInterface, BeforeFilterParticipationInterface {


    public function __construct() {
        parent::__construct();
    }

    public function after(PromotionParticipationInterface $participation) {
        parent::after($participation);

        // Send Get Qr Pincode Event
        if (!empty($participation->moment)) {
            event('promouser.getqr', $participation);
        }
    }
}
