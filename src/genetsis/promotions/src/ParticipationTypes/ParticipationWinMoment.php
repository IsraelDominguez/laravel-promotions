<?php namespace Genetsis\Promotions\ParticipationTypes;

use Carbon\Carbon;
use Genetsis\Promotions\Contracts\PromotionParticipationInterface;
use Genetsis\Promotions\Models\Moment;
use Illuminate\Support\Facades\DB;

class ParticipationWinMoment extends PromotionParticipation implements PromotionParticipationInterface {

    public function __construct(FilterParticipationInterface $filter_participation)
    {
        $this->filter_participation = $filter_participation;
    }

    public function participate() {
        $result = ParticipationResult::i();
        try {
            $this->filter_participation->before($this);

            DB::transaction(function ($result) {
                if ($moment = Moment::where('used', null)->where('date', '>', Carbon::now())->lockForUpdate()->first()) {
                    //$participation->moment()->save($moment);
                    $moment->used = Carbon::now();
                    $moment->save();

                    \Log::info(sprintf('User %s Win Moment %s in  %s', $this->getUserId(), $moment->date, $this->promo->name));
                    $result->setResult(ParticipationResult::RESULT_WIN);
                } else {
                    // Not Win
                    \Log::info(sprintf('User %s Not Win Moment in  %s', $this->getUserId(), $this->promo->name));
                    $result->setResult(ParticipationResult::RESULT_NOTWIN);
                }
                $this->save();

                $result->setStatus(ParticipationResult::STATUS_OK);
                \Log::info(sprintf('User %s participate in a Sorteo Promotion %s', $this->getUserId(), $this->promo->name));
            });

            $this->filter_participation->after($this);

        } catch (\Exception $e) {
            return $result->setParticipation($this)->setStatus(ParticipationResult::STATUS_KO)->setMessage($e->getMessage());
        }

        return $result->setParticipation($this);
    }
}
