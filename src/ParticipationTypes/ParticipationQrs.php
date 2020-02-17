<?php namespace Genetsis\Promotions\ParticipationTypes;

use ConsumerRewards\SDK\ConsumerRewards;
use ConsumerRewards\SDK\Exception\ConsumerRewardsException;
use Genetsis\Promotions\Contracts\FilterParticipationInterface;
use Genetsis\Promotions\Contracts\PromotionParticipationInterface;
use Genetsis\Promotions\Exceptions\PromotionException;
use Genetsis\Promotions\Models\Qrs;

class ParticipationQrs extends PromotionParticipation implements PromotionParticipationInterface
{

    /**
     * @var ConsumerRewards|null
     */
    protected $consumer_rewards = null;

    public function __construct(FilterParticipationInterface $filter_participation)
    {
        $this->filter_participation = $filter_participation;

        try {
            $this->consumer_rewards = new ConsumerRewards([
                'username' => config('promotion.consumer_rewards.username'),
                'password' => config('promotion.consumer_rewards.password'),
                'api' => config('promotion.consumer_rewards.api'),
                'web' => config('promotion.consumer_rewards.web'),
            ], [
                'logger' => app('log')->channel('stack')->getLogger()
            ]);
        } catch (ConsumerRewardsException $e) {
            throw new PromotionException("Fail Load Consumer Rewards");
        }
    }

    /**
     * @return ParticipationResult
     */
    public function participate()
    {
        try {
            $this->before($this);

            $participation_qr = new Qrs();

            $participation_qr->object_id = $this->consumer_rewards->getMarketing()->generateQr(
                $this->promo->qrspack->pack,
                new \ConsumerRewards\SDK\Transfer\User($this->getUserId())
            )->getObjectId();

            $this->save();

            $participation_qr->participation()->associate($this);
            $participation_qr->save();

            $this->after($this);

            \Log::info(sprintf('User %s participate in a QRs Promotion %s', $this->getUserId(), $this->promo->name));

            $participation_result = (new ParticipationResult)
                                        ->setParticipation($this)
                                        ->setStatus(ParticipationResult::STATUS_OK);

            // Send QR promotion generated
            if (!empty($this->rewards)) {
                event('promouser.getqr', $participation_result);
            }

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            $participation_result = (new ParticipationResult)
                                        ->setParticipation($this)
                                        ->setStatus(ParticipationResult::STATUS_KO)
                                        ->setMessage($e->getMessage())
                                        ->setException($e);
        }

        // Send User Participation Event
        event('promouser.participated', $participation_result);
        return $participation_result;
    }
}

