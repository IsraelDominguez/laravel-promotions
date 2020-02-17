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

    /**
     * @return ParticipationResult
     */
    public function participate() {

        try {
            $participation_result = ParticipationResult::STATUS_OK;

            $this->before($this);

            DB::transaction(function () use (&$participation_result) {
                \Log::info(sprintf('User %s participate in a WinMomment Promotion %s', $this->getUserId(), $this->promo->name));

                //Save Participation user Win or Not Win
                $this->save();

                if ($moment = Moment::where('used', null)->where('promo_id', $this->promo->id)->where('date', '<=', Carbon::now(new \DateTimeZone(config('promotion.timezone', config('app.timezone')))))->lockForUpdate()->first()) {
                    $moment->used = Carbon::now(new \DateTimeZone(config('promotion.timezone', config('app.timezone'))));
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

            $result_participation = (new ParticipationResult)
                                        ->setParticipation($this)
                                        ->setStatus(ParticipationResult::STATUS_OK)
                                        ->setResult($participation_result);

            if ($participation_result == ParticipationResult::RESULT_WIN) {
                // Send Winner Event
                event('promouser.winner', $result_participation);
            } else {
                // Send Not Winner Event
                event('promouser.notwinner', $result_participation);
            }
        } catch (\Exception $e) {
            $result_participation = (new ParticipationResult)->setParticipation($this)->setStatus(ParticipationResult::STATUS_KO)->setMessage($e->getMessage())->setException($e);
        }

        // Send User Participation Event
        event('promouser.participated', $result_participation);
        return $result_participation;
    }


}
