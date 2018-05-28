<?php namespace Genetsis\Promotions\Filters;

use Genetsis\Promotions\Contracts\AfterFilterParticipationInterface;
use Genetsis\Promotions\Contracts\BeforeFilterParticipationInterface;
use Genetsis\Promotions\Contracts\FilterParticipationInterface;
use Genetsis\Promotions\Contracts\PromotionParticipationInterface;
use Genetsis\Promotions\Exceptions\PromotionException;
use Genetsis\Promotions\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webmozart\Assert\Assert;

class GenericFilterParticipation implements FilterParticipationInterface, AfterFilterParticipationInterface, BeforeFilterParticipationInterface {

    protected $extra_participations;
    protected $promotion_service;
    protected $extra_fields_service;
    protected $rewards_service;

    public function __construct() {
        $this->extra_participations = \App::make(ExtraParticipationService::class);
        $this->promotion_service = \App::make(PromotionService::class);
        $this->extra_fields_service = \App::make(ExtraFieldsParticipationService::class);
        $this->rewards_service =  \App::make(RewardsParticipationService::class);
    }

    public function after(PromotionParticipationInterface $participation) {
        \Log::debug('After Generic Filter');
        // Save Rewards
        if ($participation->getRewards()) {
            $this->rewards_service->addRewardsParticipation($participation);
        }

        // Save Extra Fields Participations
        if ($participation->getExtraFields()) {
            $this->extra_fields_service->addExtraFieldsParticipation($participation);
        }
        // Consume Extra Participations is necessary
        $this->extra_participations->consumeUserExtraParticipation($participation);
    }

    public function before(PromotionParticipationInterface $participation) {
        try {
            \Log::debug('Before Generic Filter');
            // Check if promo is active
            Assert::true($this->promotion_service->isActive($participation->promo), sprintf('Promotion with name %s is not active.', $participation->promo->name));

            $this->promotion_service->userCanParticipate($participation->getUserId(), $participation->promo);

            if ($participation->getSponsor())
                User::where('sponsor_code', hash('crc32',$participation->getSponsor(), false))->firstOrFail();

            //Check Valid Extra Fields Defined in a Participation
            if ($participation->getExtraFields()) {
                $compare = $participation->getExtraFields()->diffKeys(
                    $participation->promo->extra_fields->mapWithKeys(function ($item) {
                        return [$item->key => $item->name];
                    })
                );

                if (count($compare) > 0)
                    throw new PromotionException('Invalid Extra Fields');
            }

            //Check Valid Rewards Defined in a Participation
            if ($participation->getRewards()) {
                $compare = $participation->getRewards()->diffKeys(
                    $participation->promo->rewards->mapWithKeys(function ($item) {
                        return [$item->key => $item->name];
                    })
                );

                if (count($compare) > 0)
                    throw new PromotionException('Invalid Rewards');
            }

        } catch (ModelNotFoundException $e) {
            throw new PromotionException('Invalid Sponsor Code');
        } catch (\InvalidArgumentException $e) {
            throw new PromotionException($e->getMessage());
        } catch (PromotionException $e) {
            throw $e;
        }
    }
}