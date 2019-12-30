<?php namespace Genetsis\Promotions\Events;

use Genetsis\Admin\Utils\GTM;
use Genetsis\Promotions\ParticipationTypes\PromotionParticipation;

class GTMPromoSubscriber
{

    public function onUserGetQr(PromotionParticipation $participation) {
        $event = \App::make('GtmEvents');
        $event->send(GTM\EventFactory::viewPage($participation->promo->key));
    }

    public function onUserRedeem(PromotionParticipation $participation) {
        $event = \App::make('GtmEvents');
        $event->send(GTM\EventFactory::redeem($participation->promo->key));
    }

    public function onUserWinner(PromotionParticipation $participation) {
        $event = \App::make('GtmEvents');
        $event->send(GTM\EventFactory::winnner($participation->promo->key));
    }

    public function onUserReferred(PromotionParticipation $participation) {
        $event = \App::make('GtmEvents');
        $event->send(GTM\EventFactory::referred($participation->getSponsor()));
    }

    public function onUserParticipation(PromotionParticipation $participation) {
        $event = \App::make('GtmEvents');
        $event->send(GTM\EventFactory::participation($participation->promo->key));
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
