<?php namespace Genetsis\Promotions\PromotionTypes;

use Genetsis\Promotions\Contracts\PromotionTypeInterface;
use Genetsis\Promotions\Models\ExtraFields;
use Genetsis\Promotions\Models\Promotion;
use Genetsis\Promotions\Models\Rewards;
use Genetsis\Promotions\Models\Templates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Metadata\Tests\Fixtures\TestParent;

class GenericPromotion implements PromotionTypeInterface
{
    /* @var Promotion $promotion
     */
    protected $promotion;

    public function __construct() {
    }

    public function setPromotion(Promotion $promotion) {
        $this->promotion = $promotion;
        return $this;
    }

    public function save(Request $request)
    {
        if (config('promotion.front_templates_enabled')) {
            if (config('promotion.front_share_enabled'))
                $this->saveSeo($request);
            if (config('promotion.front_design_enabled'))
                $this->saveDesign($request);
            if (config('promotion.front_pages_enabled'))
                $this->savePages($request);
        }

        if (config('promotion.extra_fields_enabled'))
            $this->saveExtraFields($request);

        if (config('promotion.rewards_fields_enabled'))
            $this->saveRewards($request);
    }

    /**
     * @param Request $request
     * @param $extra_field
     */
    private function saveRewards(Request $request): void
    {
        if ($rewards_keys = $request->get('reward_keys')) {
            foreach ($this->promotion->rewards as $reward) {
                if (!in_array($reward->key, $rewards_keys)) {
                    Log::debug("Delete reward field: " . $reward->key);
                    Rewards::destroy($reward->key);
                }
            }

            foreach ($rewards_keys as $key => $reward) {
                if ($reward != null) {
                    if ($this->promotion->rewards->contains('key', $reward)) {
                        Log::debug("Edit reward: " . $reward);
                        Rewards::where('key', $reward)
                            ->update(['name' => $request->get('reward_names')[$key], 'stock' => ($request->get('reward_stocks')[$key]) ?: 0]);
                    } else {
                        Log::debug("New reward: " . $reward);
                        $rewardField = new Rewards();
                        $rewardField->key = $reward;
                        $rewardField->name = $request->get('reward_names')[$key];
                        $rewardField->stock = ($request->get('reward_stocks')[$key]) ?: 0;
                        $rewardField->promo_id = $this->promotion->id;
                        $rewardField->save();
                    }
                }
            }
        }
    }

    /**
     * @param Request $request
     */
    private function saveExtraFields(Request $request): void
    {
        if ($extra_fields_keys = $request->get('extra_field_keys')) {
            foreach ($this->promotion->extra_fields as $extra_field) {
                if (!in_array($extra_field->key, $extra_fields_keys)) {
                    Log::debug("Delete extra field: " . $extra_field->key);
                    ExtraFields::destroy($extra_field->key);
                }
            }

            foreach ($extra_fields_keys as $key => $extra_field) {
                if ($extra_field != null) {
                    if ($this->promotion->extra_fields->contains('key', $extra_field)) {
                        Log::debug("Edit extra field: " . $extra_field);
                        ExtraFields::where('key', $extra_field)
                            ->update([
                                'name' => $request->get('extra_field_names')[$key],
                                'type' => $request->get('extra_field_types')[$key]
                            ]);
                    } else {
                        Log::debug("New extra field: " . $extra_field);

                        $extraField = new ExtraFields();
                        $extraField->key = $extra_field;
                        $extraField->name = $request->get('extra_field_names')[$key];
                        $extraField->type = $request->get('extra_field_types')[$key];
                        $extraField->promo_id = $this->promotion->id;

                        Log::debug("Extra field: type" . $extraField->type);

                        $extraField->save();
                    }
                }
            }
        }
    }

    /**
     * @param Request $request
     */
    private function saveSeo(Request $request): void
    {
        if ($request->hasFile('image')&&($request->file('image')->isValid())) {
            $image = $request->image->storeAs('promoimg', $this->promotion->key.'.'.$request->file('image')->getClientOriginalExtension(), 'public');
        } elseif ($request->has('remove_image')) {
            $image = null;
        } else {
            $image = $this->promotion->seo->image;
        }

        $this->promotion->seo()->updateOrCreate([
            'promo_id' => $this->promotion->id
        ], [
            'title' => $request->input('title'),
            'facebook' => $request->input('facebook'),
            'twitter' => $request->input('twitter'),
            'whatsapp' => $request->input('whatsapp'),
            'text_share' => $request->input('text_share'),
            'image' => $image
        ]);
    }

    /**
     * @param Request $request
     */
    private function saveDesign(Request $request) {

        if ($request->hasFile('background_image')) {
            $log_file = $request->file('background_image');
            $log_content = $log_file->openFile()->fread($log_file->getSize());
            $background_image  = base64_encode($log_content);
        } elseif ($request->has('remove_background')) {
            $background_image = null;
        }

        $this->promotion->design()->updateOrCreate([
            'promo_id' => $this->promotion->id
        ], [
            'background_image' => $background_image ?? $this->promotion->design->background_image ?? '',
            'background_color' => $request->input('background_color'),
        ]);
    }

    /**
     * @param Request $request
     */
    private function savePages(Request $request) {
        $this->saveTemplates('initial_page', $request->input('initial_page_data', ''), $request->input('initial_page_template',''), $request);

        $this->saveTemplates('result_page', $request->input('result_page_data', ''), $request->input('result_page_template',''), $request);
    }

    /**
     * @param string $page
     * @param $content
     * @param string $template
     * @param Request $request
     */
    private function saveTemplates(string $page, $content, $template = 'left', Request $request) {
        $arr_content = json_decode($content, true);

        if (!empty($content)) {
            if ($request->hasFile('promo_image_' . $page . '_template_' . $template) && ($request->file('promo_image_' . $page . '_template_' . $template)->isValid())) {
                $promo_image = $request->file('promo_image_' . $page . '_template_' . $template)->storeAs('promoimg', $this->promotion->key . '-' . $page . '.jpg', 'public');
                $arr_content = array_merge(['promo_image' => $promo_image], $arr_content);
            } elseif ($request->has('delete_image_'.$page.'_template_'.$template)) {
                if (array_key_exists('promo_image', $arr_content)) {
                    $arr_content['promo_image'] = '';
                }
            }
        }
        $this->promotion->templates()->updateOrCreate([
            'promo_id' => $this->promotion->id,
            'page' => $page,
        ], [
            'template' => $template ?? 'left',
            'content' => (count($arr_content??[]) == 0) ? '' : json_encode($arr_content)
        ]);
    }
}
