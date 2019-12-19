<?php namespace Genetsis\Promotions\ParticipationTypes;

use Genetsis\Promotions\Contracts\FilterParticipationInterface;
use Genetsis\Promotions\Contracts\PromotionParticipationInterface;
use Genetsis\Promotions\Services\ExtraFieldsParticipationService;

class ParticipationSorteo extends PromotionParticipation implements PromotionParticipationInterface
{
    public function __construct(FilterParticipationInterface $filter_participation)
    {
        $this->filter_participation = $filter_participation;
    }


    public function participate()
    {
        try {
            $this->before($this);

            $this->save();

            $this->after($this);

            \Log::info(sprintf('User %s participate in a Sorteo Promotion %s', $this->getUserId(), $this->promo->name));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return ParticipationResult::i()->setParticipation($this)->setStatus(ParticipationResult::STATUS_KO)->setMessage($e->getMessage())->setException($e);
        }

        return ParticipationResult::i()->setParticipation($this)->setStatus(ParticipationResult::STATUS_OK);
    }
}

