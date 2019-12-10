<?php namespace Genetsis\Promotions\Events;


use Genetsis\Admin\Utils\GTM;
use Genetsis\Promotions\ParticipationTypes\PromotionParticipation;

class GTMPromoSubscriber
{

    public function onUserGetQr(PromotionParticipation $participation) {
        GTM::sendGetQr($participation->promo->key);
    }

    public function onUserRedeem(PromotionParticipation $participation) {
        GTM::sendRedeem($participation->promo->key);
    }

    public function onUserWinner(PromotionParticipation $participation) {
        GTM::sendWinnner($participation->promo->key);
    }

    public function onUserReferred(PromotionParticipation $participation) {
        GTM::sendReferred($participation->getSponsor());
    }

    public function onUserParticipation(PromotionParticipation $participation) {

        GTM::sendParticipation($participation->promo->key);
    }


    public function subscribe($events)
    {
        $events->listen('promouser.participated', GTMPromoSubscriber::class.'@onUserParticipation');
        $events->listen('promouser.referred', GTMPromoSubscriber::class.'@onUserReferred');
        $events->listen('promouser.winner', GTMPromoSubscriber::class.'@onUserWinner');
        $events->listen('promouser.redeem', GTMPromoSubscriber::class.'@onUserRedeem');
        $events->listen('promouser.getqr', GTMPromoSubscriber::class.'@onUserGetQr');
    }

}
