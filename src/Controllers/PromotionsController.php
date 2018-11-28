<?php namespace Genetsis\Promotions\Controllers;

use Genetsis\Admin\Controllers\AdminController;
use Carbon\Carbon;
use Genetsis\Promotions\Models\Campaign;
use Genetsis\Promotions\Models\ExtraFields;
use Genetsis\Promotions\Models\Participation;
use Genetsis\Promotions\Models\Promotion;
use Genetsis\Promotions\Models\PromoType;
use Genetsis\Promotions\Models\QrsPack;
use Genetsis\Promotions\Models\Rewards;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Psy\VarDumper\Dumper;
use Yajra\DataTables\DataTables;

class PromotionsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        \View::share('section', 'promotion');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $promotions = Promotion::latest()->paginate(10);
        return view('promotion::promotions.index',compact('promotions'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $campaigns = Campaign::latest()->get();
        $promo_types = PromoType::all();
        return view('promotion::promotions.create', compact('campaigns', 'promo_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'unique:promo|required|max:255',
            'campaign_id' => 'required|integer',
            'type_id' => 'required|integer',
            'max_user_participations' => 'nullable|integer|max:2',
            'max_user_participations_by_day' => 'nullable|integer|max:2',
            'ends' => 'nullable|after:starts',
            'key' => 'required|alpha_dash|max:50',
            'entry_point' => 'nullable|alpha_dash|max:100',
            'has_mgm' => 'nullable'
        ]);

        $request->merge(array('has_mgm' => $request->has('has_mgm') ? true : false));

        $promotion = Promotion::create($request->all());

        if ($promotion->type->code == PromoType::QRS_TYPE) {
            //TODO: generate pack in Consumer Rewards

            QrsPack::create([
                'promo_id' => $promotion->id,
                'pack' => $request->get('pack'),
                'key' => $request->get('pack_key'),
                'name' => $request->get('pack_name'),
                'max' => $request->get('pack_max')
            ]);
        }

        if ($extra_fields_keys = $request->get('extra_field_keys')) {
            foreach ($extra_fields_keys as $key => $extra_field) {
                if ($extra_field != null) {
                    $extraField = new ExtraFields();
                    $extraField->key = $extra_field;
                    $extraField->name = $request->get('extra_field_names')[$key];
                    $extraField->promo_id = $promotion->id;
                    $extraField->save();
                }
            }
        }

        if ($rewards_keys = $request->get('reward_keys')) {
            foreach ($rewards_keys as $key => $reward) {
                if ($reward != null) {
                    $rewardField = new Rewards();
                    $rewardField->key = $reward;
                    $rewardField->name = $request->get('reward_names')[$key];
                    $rewardField->stock = ($request->get('reward_stocks')[$key]) ? ($request->get('reward_stocks')[$key]) : 0;
                    $rewardField->promo_id = $promotion->id;
                    $rewardField->save();
                }
            }
        }

        return redirect()->route('promotions.home')
            ->with('success','Promotion created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $promotion = Promotion::findOrFail($id);

        $unique_users = ($promotion->participations->map(function($participation){
            return $participation->user_id;
        })->unique()->count());

        $days = $promotion->participations->groupBy(function($participation) {
           return Carbon::createFromFormat('Y-m-d H:i:s', $participation->date)->dayOfWeek;
        })->map(function($item, $key){
            return count($item);
        })->union(collect(array_fill(0,7,0)))->all();

        $hours = $promotion->participations->groupBy(function($participation) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $participation->date)->hour;
        })->map(function($item, $key){
            return count($item);
        })->union(collect(array_fill(0,24, 0)))->all();

        $participations = $promotion->participations->groupBy(function($participation) {
            return strtotime(Carbon::createFromFormat('Y-m-d H:i:s', $participation->date)->format('Y-m-d'))*1000;
        })->map(function($item, $key) {
            return count($item);
        })->all();

        if ($promotion->type->code == PromoType::PINCODE_TYPE) {
            $pincodes = new \stdClass();
            $pincodes->all = $promotion->codes;

            $pincodes->used = $promotion->participations->filter(function($p) {
                return $p->code;
            })->count();
        }

        if ($promotion->type->code == PromoType::MOMENT_TYPE) {
            $moments = new \stdClass();
            $moments->all = $promotion->moment;

            $moments->used = $promotion->participations->filter(function($p) {
                return $p->moment;
            })->count();
        }

        return view('promotion::promotions.show',compact('promotion','unique_users', 'participations', 'days', 'hours', 'pincodes', 'moments'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function moments(Request $request, $id) {

        if ($request->ajax()) {
            $promotion = Promotion::findOrFail($id);

            return DataTables::of($promotion->moment->sortByDesc('used')->map(function ($m) {
                return $m;
            }))->make(true);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function pincodes(Request $request, $id) {

        if ($request->ajax()) {
            $promotion = Promotion::findOrFail($id);

            return DataTables::of($promotion->codes->sortByDesc('used')->map(function ($m) {
                return $m;
            }))->make(true);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $campaigns = Campaign::latest()->get();
        $promotion = Promotion::findOrFail($id);
        $promo_types = PromoType::all();

        return view('promotion::promotions.edit',compact('promotion', 'campaigns','promo_types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'name' => ['required',
                        Rule::unique('promo')->ignore($id),
                        'max:50'
            ],
            'campaign_id' => ['required','integer'],
            'type_id' => 'required|integer',
            'max_user_participations' => 'nullable|digits_between:1,99',
            'max_user_participations_by_day' => 'nullable|digits_between:1,99',
            'ends' => 'nullable|after:starts',
            'key' => 'required|alpha_dash|max:50',
            'entry_point' => 'nullable|alpha_dash|max:100',
            'has_mgm' => 'nullable'
        ]);

        $request->merge(array('has_mgm' => $request->has('has_mgm') ? true : false));

        $promotion = Promotion::find($id);
        $promotion->update($request->all());

        if ($promotion->type->code == PromoType::QRS_TYPE) {
            //TODO: generate pack in Consumer Rewards

            QrsPack::where('promo_id', $promotion->id)
                ->update([
                    'pack' => $request->get('pack'),
                    'key' => $request->get('pack_key'),
                    'name' => $request->get('pack_name'),
                    'max' => $request->get('pack_max')
                ]);
        }

        if ($extra_fields_keys = $request->get('extra_field_keys')) {
            foreach ($promotion->extra_fields as $extra_field) {
                if (!in_array($extra_field->key, $extra_fields_keys)) {
                    Log::debug("Elimino extra field: ". $extra_field->key);
                    ExtraFields::destroy($extra_field->key);
                }
            }

            foreach ($extra_fields_keys as $key => $extra_field) {
                if ($extra_field != null) {
                    if ($promotion->extra_fields->contains('key',$extra_field)) {
                        Log::debug("Edit extra field: ". $extra_field);
                        ExtraFields::where('key', $extra_field)
                            ->update(['name' => $request->get('extra_field_names')[$key]]);
                    } else {
                        Log::debug("New extra field: ". $extra_field);
                        $extraField = new ExtraFields();
                        $extraField->key = $extra_field;
                        $extraField->name = $request->get('extra_field_names')[$key];
                        $extraField->promo_id = $promotion->id;
                        $extraField->save();
                    }
                }
            }
        }

        if ($rewards_keys = $request->get('reward_keys')) {
            foreach ($promotion->rewards as $reward) {
                if (!in_array($reward->key, $rewards_keys)) {
                    Log::debug("Elimino reward field: ". $reward->key);
                    Rewards::destroy($reward->key);
                }
            }


            foreach ($rewards_keys as $key => $reward) {
                if ($reward != null) {
                    if ($promotion->rewards->contains('key',$reward)) {
                        Log::debug("Edit reward: ". $reward);
                        Rewards::where('key', $reward)
                            ->update(['name' => $request->get('reward_names')[$key], 'stock'=>($request->get('reward_stocks')[$key]) ? ($request->get('reward_stocks')[$key]) : 0]);
                    } else {
                        Log::debug("New reward: ". $extra_field);
                        $rewardField = new Rewards();
                        $rewardField->key = $reward;
                        $rewardField->name = $request->get('reward_names')[$key];
                        $rewardField->stock = ($request->get('reward_stocks')[$key]) ? ($request->get('reward_stocks')[$key]) : 0;
                        $rewardField->promo_id = $promotion->id;
                        $rewardField->save();
                    }
                }
            }
        }

        return redirect()->route('promotions.home')
            ->with('success','Promotion updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Promotion::find($id)->delete();
        return redirect()->route('promotions.home')
            ->with('success','Promotion deleted successfully');
    }

}
