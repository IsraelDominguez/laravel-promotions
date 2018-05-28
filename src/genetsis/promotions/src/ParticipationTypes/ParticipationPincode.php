<?php namespace Genetsis\Promotions\ParticipationTypes;

use Carbon\Carbon;
use Genetsis\Promotions\Contracts\FilterParticipationInterface;
use Genetsis\Promotions\Contracts\PromotionParticipationInterface;
use Genetsis\Promotions\Models\Codes;
use Illuminate\Support\Facades\DB;

class ParticipationPincode extends PromotionParticipation implements PromotionParticipationInterface {

    /**
     * Pincode
     * @var string
     */
    protected $pincode = '';

    public function __construct(FilterParticipationInterface $filter_participation)
    {
        $this->filter_participation = $filter_participation;
    }

    public function participate() {

        try {
            $this->before($this);

            DB::transaction(function () {
                \Log::info(sprintf('User %s participate in a Pincode Promotion %s with Pincode %s', $this->getUserId(), $this->promo->name, $this->getPincode()));

                if ($code = Codes::where('code', $this->getPincode())
                    ->where('used', null)
                    ->where(function($q) {
                        $q->whereNull('expires')->orWhereDate('expires', '>=', Carbon::today()->toDateString());
                    })
                    ->first()) {

                    $this->save();

                    $code->participation()->associate($this);
                    $code->used = Carbon::now();
                    $code->save();

                } else {
                    \Log::info('Pincode Promotion: used or invalid Pincode');
                    throw new \Exception('Used or Invalid Pincode');
                }
            });

            $this->after($this);

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
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
