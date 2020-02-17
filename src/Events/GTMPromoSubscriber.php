<?php namespace Genetsis\Promotions\Events;

use Genetsis\Admin\Utils\GTM;
use Genetsis\Promotions\ParticipationTypes\ParticipationResult;
use Genetsis\Promotions\ParticipationTypes\PromotionParticipation;

class GTMPromoSubscriber
{

    public function onUserGetQr(ParticipationResult $participation_result) {
        $event = \App::make('GtmEvents');
        $event->send(GTM\EventFactory::getQr($participation_result->getParticipation()->promo->key));
    }

    public function onUserRedeem(ParticipationResult $participation_result) {
        $event = \App::make('GtmEvents');
        $event->send(GTM\EventFactory::redeem($participation_result->getParticipation()->promo->key));
    }

    public function onUserWinner(ParticipationResult $participation_result) {
        $event = \App::make('GtmEvents');
        $event->send(GTM\EventFactory::winnner($participation_result->getParticipation()->promo->key));
    }

    public function onUserReferred(PromotionParticipation $participation) {
        $event = \App::make('GtmEvents');
        $event->send(GTM\EventFactory::referred($participation->getSponsor()));
    }

    public function onUserParticipation(ParticipationResult $participation_result) {
        $event = \App::make('GtmEvents');
        $event->send(GTM\EventFactory::participation($participation_result->getParticipation()->promo->key));
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
