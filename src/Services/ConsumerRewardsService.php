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
     * Get Qr by Id
     *
     * @param string $qr_id
     * @return \ConsumerRewards\SDK\Transfer\Qr
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getQrById(string $qr_id) {

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
