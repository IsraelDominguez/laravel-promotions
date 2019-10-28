<?php namespace Genetsis\Promotions\ParticipationTypes;

use Carbon\Carbon;
use Genetsis\Promotions\Contracts\FilterParticipationInterface;
use Genetsis\Promotions\Contracts\PromotionParticipationInterface;
use Genetsis\Promotions\Exceptions\InvalidPincodeException;
use Genetsis\Promotions\Exceptions\PromotionException;
use Genetsis\Promotions\Models\Codes;
use Genetsis\Promotions\Services\PromotionService;
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
            $participation_result = ParticipationResult::STATUS_OK;

            $this->before($this);

            DB::transaction(function () use (&$participation_result) {
                \Log::info(sprintf('User %s participate in a Pincode Promotion %s with Pincode %s', $this->getUserId(), $this->promo->name, $this->getPincode()));

                //$code = $this->promotion_service->getPincodeByCode($this->getPincode(), $this->promo);

                $code = Codes::where('code', $this->getPincode())
                    ->where('promo_id', $this->getPromoId())
                    ->where(function ($q) {
                        $q->whereNull('expires')->orWhereDate('expires', '>=', Carbon::today(new \DateTimeZone(config('promotion.timezone', config('app.timezone'))))->toDateString());
                    })
                    ->first();

                if (empty($code) || ($code->used != null)) {
                    throw new InvalidPincodeException("Pincode Used or Invalid");
                } else {
                    $this->save();

                    $code->participation()->associate($this);
                    $code->used = Carbon::now(new \DateTimeZone(config('promotion.timezone', config('app.timezone'))));
                    $code->save();

                    if ($code->win_code) {
                        $participation_result = ParticipationResult::RESULT_WIN;
                    } else {
                        $participation_result = ParticipationResult::RESULT_NOTWIN;
                    }
                }
            });

            $this->after($this);

        } catch (InvalidPincodeException $e) {
            return ParticipationResult::i()->setParticipation($this)->setStatus(ParticipationResult::STATUS_KO)->setMessage($e->getMessage())->setResult(ParticipationResult::RESULT_INVALID_PINCODE);
        } catch (\Exception $e) {
            return ParticipationResult::i()->setParticipation($this)->setStatus(ParticipationResult::STATUS_KO)->setMessage($e->getMessage());
        }

        return ParticipationResult::i()->setParticipation($this)->setStatus(ParticipationResult::STATUS_OK)->setResult($participation_result);
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
