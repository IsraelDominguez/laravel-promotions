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
                'username' => config('promotion.consumer_rewards_username'),
                'password' => config('promotion.consumer_rewards_password'),
                'api' => config('promotion.consumer_rewards_api'),
                'web' => config('promotion.consumer_rewards_web'),
            ], [
                'logger' => \Log::getMonolog()
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
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return ParticipationResult::i()->setParticipation($this)->setStatus(ParticipationResult::STATUS_KO)->setMessage($e->getMessage());
        }

        return ParticipationResult::i()->setParticipation($this)->setStatus(ParticipationResult::STATUS_OK);
    }
}

