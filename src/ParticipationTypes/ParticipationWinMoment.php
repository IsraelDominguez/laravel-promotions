<?php namespace Genetsis\Promotions\ParticipationTypes;

use Carbon\Carbon;
use Genetsis\Promotions\Contracts\FilterParticipationInterface;
use Genetsis\Promotions\Contracts\PromotionParticipationInterface;
use Genetsis\Promotions\Exceptions\PromotionException;
use Genetsis\Promotions\Models\Moment;
use Illuminate\Support\Facades\DB;

class ParticipationWinMoment extends PromotionParticipation implements PromotionParticipationInterface {

    public function __construct(FilterParticipationInterface $filter_participation)
    {
        $this->filter_participation = $filter_participation;
    }

    public function participate() {

        try {
            $participation_result = ParticipationResult::STATUS_OK;

            $this->before($this);

            DB::transaction(function () use (&$participation_result) {
                \Log::info(sprintf('User %s participate in a WinMomment Promotion %s', $this->getUserId(), $this->promo->name));

                if ($moment = Moment::where('used', null)->where('promo_id', $this->promo->id)->where('date', '<=', Carbon::now())->lockForUpdate()->first()) {

                    $this->save();

                    $moment->used = Carbon::now();
                    $moment->participation()->associate($this);

                    $moment->save();

                    \Log::info(sprintf('User %s Win Moment %s in  %s', $this->getUserId(), $moment->date, $this->promo->name));
                    $participation_result = ParticipationResult::RESULT_WIN;
                } else {

                    \Log::info(sprintf('User %s Not Win Moment in  %s', $this->getUserId(), $this->promo->name));
                    $participation_result = ParticipationResult::RESULT_NOTWIN;
                }
            });

            $this->after($this);

        } catch (\Exception $e) {
            return ParticipationResult::i()->setParticipation($this)->setStatus(ParticipationResult::STATUS_KO)->setMessage($e->getMessage());
        }

        return ParticipationResult::i()->setParticipation($this)->setStatus($participation_result);
    }


}
