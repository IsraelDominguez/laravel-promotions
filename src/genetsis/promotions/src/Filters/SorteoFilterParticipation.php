<?php namespace Genetsis\Promotions\Filters;

use Genetsis\Promotions\Contracts\AfterFilterParticipationInterface;
use Genetsis\Promotions\Contracts\BeforeFilterParticipationInterface;
use Genetsis\Promotions\Contracts\FilterParticipationInterface;
use Genetsis\Promotions\Contracts\PromotionParticipationInterface;
use Genetsis\Promotions\Exceptions\PromotionException;
use Genetsis\Promotions\Models\User;
use Genetsis\Promotions\Services\ExtraFieldsParticipationService;
use Genetsis\Promotions\Services\ExtraParticipationService;
use Genetsis\Promotions\Services\PromotionService;
use Genetsis\Promotions\Services\RewardsParticipationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webmozart\Assert\Assert;

class SorteoFilterParticipation extends GenericFilterParticipation implements FilterParticipationInterface, AfterFilterParticipationInterface, BeforeFilterParticipationInterface {

    public function __construct() {
        parent::__construct();
    }

    public function after(PromotionParticipationInterface $participation) {
        parent::before($participation);
    }

    public function before(PromotionParticipationInterface $participation) {
        parent::before($participation);
    }
}