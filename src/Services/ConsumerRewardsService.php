<?php namespace Genetsis\Promotions\Services;


use ConsumerRewards\SDK\ConsumerRewards;
use ConsumerRewards\SDK\Exception\ConsumerRewardsException;
use ConsumerRewards\SDK\Exception\InvalidQrException;
use ConsumerRewards\SDK\Exception\RedeemQrException;
use Genetsis\Promotions\Exceptions\PromotionException;

class ConsumerRewardsService
{
    /**
     * @var ConsumerRewards|null
     */
    protected $consumer_rewards = null;

    public function __construct() {
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
     * @param string $qr_id
     * @return \ConsumerRewards\SDK\Transfer\Qr
     * @throws \Exception
     * @throws ConsumerRewardsException
     * @throws RedeemQrException
     * @throws InvalidQrException
     */
    public function getQrById($qr_id) {

        try {
            $status = $this->consumer_rewards->getMarketing()->checkById($qr_id);

            if ($status === \ConsumerRewards\SDK\Transfer\Qr::STATUS_VALID) {
                return $this->consumer_rewards->getQrs()->findById($qr_id);
            } else if ($status === \ConsumerRewards\SDK\Transfer\Qr::STATUS_REDEEM) {
                throw new RedeemQrException("Qr is Redeemed");
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return ConsumerRewards|null
     */
    public function getConsumerRewards() {
        return $this->consumer_rewards;
    }
}