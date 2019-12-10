<?php namespace Genetsis\Promotions\Filters;

use Genetsis\Promotions\Contracts\BeforeFilterParticipationInterface;
use Genetsis\Promotions\Contracts\FilterParticipationInterface;
use Genetsis\Promotions\Contracts\PromotionParticipationInterface;
use Genetsis\Promotions\Exceptions\PromotionException;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webmozart\Assert\Assert;

class WinMomentFilterParticipation extends GenericFilterParticipation implements FilterParticipationInterface, BeforeFilterParticipationInterface {


    public function __construct() {
        parent::__construct();
    }

    public function after(PromotionParticipationInterface $participation) {
        parent::after($participation);

        if (!empty($participation->moment)) {
            // Send Winner Event
            event('promouser.winner', $participation);
        } else {
            // Send Not Winner Event
            event('promouser.notwinner', $participation);
        }
    }

}
