<?php namespace Genetsis\Promotions\Repositories;

use ConsumerRewards\SDK\Exception\ConsumerRewardsException;
use ConsumerRewards\SDK\Transfer\Configuration;
use ConsumerRewards\SDK\Transfer\Pack;
use Genetsis\Promotions\Contracts\PromotionRepositoryInterface;
use Genetsis\Promotions\Models\Promotion;
use Genetsis\Promotions\Models\QrsPack;
use Genetsis\Promotions\Services\ConsumerRewardsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QrsPromotionType extends GenericPromotion implements PromotionRepositoryInterface {

    public function __construct(Promotion $promotion, Request $request) {
        parent::__construct($promotion, $request);
    }

    public function save(Request $request) {
        try {
            parent::save($request);

            if ($request->get('pack')!=null) {
                $pack_id = $request->get('pack');
            } else {
                $configurations[] = new Configuration("max", Configuration::CONFIGURATION_TYPE_INTEGER, $request->get('pack_max'));
                $pack = new Pack();
                $pack->setKey($request->get('pack_key'))->setDisplayName($request->get('pack_name'))->setConfigurations($configurations)->setType('consumer_rewards');

                $consumerRewardsService = new ConsumerRewardsService();
                $pack_created = $consumerRewardsService->getConsumerRewards()->getMarketing()->createPack($pack);
                $pack_id = $pack_created->getObjectId();
            }

            QrsPack::updateOrCreate(
                ['promo_id' => $this->promotion->id],
                [
                    'pack' => $pack_id,
                    'key' => $request->get('pack_key'),
                    'name' => $request->get('pack_name'),
                    'max' => $request->get('pack_max')
                ]);
        } catch (ConsumerRewardsException $e) {
            Log::error($e->getMessage());
        }
    }


}
