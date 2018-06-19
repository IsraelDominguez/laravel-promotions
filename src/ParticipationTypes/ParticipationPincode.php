<?php namespace Genetsis\Promotions\ParticipationTypes;

use Carbon\Carbon;
use Genetsis\Promotions\Contracts\FilterParticipationInterface;
use Genetsis\Promotions\Contracts\PromotionParticipationInterface;
use Genetsis\Promotions\Services\PromotionService;
use Illuminate\Support\Facades\DB;

class ParticipationPincode extends PromotionParticipation implements PromotionParticipationInterface {

    /**
     * Pincode
     * @var string
     */
    protected $pincode = '';

    protected $promotion_service;

    public function __construct(FilterParticipationInterface $filter_participation)
    {
        $this->filter_participation = $filter_participation;
        $this->promotion_service = \App::make(PromotionService::class);
    }

    public function participate() {

        try {
            $this->before($this);

            DB::transaction(function () {
                \Log::info(sprintf('User %s participate in a Pincode Promotion %s with Pincode %s', $this->getUserId(), $this->promo->name, $this->getPincode()));

                $code = $this->promotion_service->getPincodeByCode($this->getPincode(), $this->promo);

                $this->save();

                $code->participation()->associate($this);
                $code->used = Carbon::now();
                $code->save();
            });

            $this->after($this);

        } catch (\Exception $e) {
            return ParticipationResult::i()->setParticipation($this)->setStatus(ParticipationResult::STATUS_KO)->setMessage($e->getMessage());
        }

        return ParticipationResult::i()->setParticipation($this)->setStatus(ParticipationResult::STATUS_OK);
    }


    /**
     * @return string
     */
    public function getPincode()
    {
        return $this->pincode;
    }

    /**
     * @param $pincode
     * @return $this
     */
    public function setPincode($pincode)
    {
        $this->pincode = $pincode;
        return $this;
    }
}
