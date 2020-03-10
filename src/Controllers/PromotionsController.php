<?php namespace Genetsis\Promotions\Controllers;

use Genetsis\Admin\Controllers\AdminController;
use Carbon\Carbon;

use Genetsis\Druid\Rest\Exceptions\RestApiException;
use Genetsis\Promotions\Models\Campaign;
use Genetsis\Promotions\Models\Entrypoint;
use Genetsis\Promotions\Models\Participation;
use Genetsis\Promotions\Models\Promotion;
use Genetsis\Promotions\Models\PromoType;

use Genetsis\Promotions\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
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
        return view('promotion::promotions.index');
    }


    /**
     * Api for DataTables
     *
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function get(Request $request)
    {
        $promotions = Promotion::with(['campaign', 'type'])->withCount('participations');

        return \datatables()->eloquent($promotions)
            ->addColumn('active', function($promotion){
                return $promotion->isActive() ? '<label class="badge badge-success">Yes</label>' : 'No';
            })
            ->addColumn('options', function ($promotion) {
                return '
                    <div class="actions" style="width:100px">
                    <a class="actions__item zmdi zmdi-eye" href="' . route('promotions.show', $promotion->id) . '"></a>
                    <a class="actions__item zmdi zmdi-edit" href="' . route('promotions.edit', $promotion->id) . '"></a>
                    <a class="actions__item zmdi zmdi-link" href="' . url($promotion->key) . '" target="_blank"></a>
                    </div>
                    ';
            })
            ->addColumn('delete', 'promotion::partials.deletetable')
            ->rawColumns(['active', 'options', 'delete'])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $campaigns = Campaign::latest()->get();
        $promo_types = PromoType::enabled()->get();
        return view('promotion::promotions.create', compact('campaigns', 'promo_types'));
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

        $mgm = $promotion->participations->filter(function($p){
            return $p->sponsor;
        })->count();

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
        })->sortBy(function($item, $key) {
            return $key;
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

        return view('promotion::promotions.show',compact('promotion','unique_users', 'participations', 'days', 'hours', 'pincodes', 'moments', 'mgm'));
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
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Genetsis\Promotions\Exceptions\PromotionException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $promotion = $this->save($request, null);

        return redirect()->route('promotions.edit', $promotion->id)
            ->with('success','Promotion created successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Genetsis\Promotions\Exceptions\PromotionException
     */
    public function update(Request $request, $id)
    {
        $promotion = $this->save($request, $id);

        return redirect()->route('promotions.edit', $promotion->id)
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
        //TODO: delete all files asociated (images, legal,...)
        try {
            Promotion::find($id)->delete();

            return response()->json(['Status' => 'Ok', 'message'=>'Promotion Deleted']);
        } catch (\Exception $e) {
            return response()->json('Error:'.$e->getMessage(), 500);
        }
    }

    /**
     * Get Request Validation
     *
     * @param Request $request
     * @param $id
     * @return array all validations
     */
    private function getValidations(Request $request, $id) {
        $validations = [
            'name' => 'required|unique:promo|max:250',
            'campaign_id' => ['required','integer'],
            'type_id' => 'required|integer',
            'max_user_participations' => 'nullable|digits_between:1,99999',
            'max_user_participations_by_day' => 'nullable|digits_between:1,99',
            'starts' => 'required',
            'ends' => 'nullable|after:starts',
            'key' => 'required|unique:promo|alpha_dash|max:250',
            'entrypoint_id' => 'nullable|alpha_dash|max:200',
            'has_mgm' => 'nullable',
            'is_public' => 'nullable',
            'legal' => 'nullable|max:100',
            'legal_file' => 'nullable|file|mimes:pdf',
            'pack' => 'nullable|alpha_num|max:100',
            'pack_key' => 'nullable|alpha_dash|max:100',
            'pack_name' => 'nullable|max:100',
            'pack_max' => 'nullable|integer',
            'win_moment_file' => 'nullable',
            'pincodes_file' => 'nullable',
            'lang' => 'nullable|max:4',
            'timezone' => 'nullable|timezone'
        ];

        // Edit Promotion
        if ($id != null) {
            $validations['name'] = ['required', Rule::unique('promo')->ignore($id), 'max:250'];
            $validations['key'] = ['required', Rule::unique('promo')->ignore($id),'alpha_dash', 'max:250'];
        }

        if (config('promotion.front_share_enabled') && config('front_templates_enabled')) {
            $validations['title'] = 'required';
            if ($id == null) {
                $validations['image'] = 'required';
            }

            if ($request->has('has_mgm')) {
                $validations['text_share'] = 'required';
            }
        }

        return $validations;
    }

    /**
     * Save or Update Promotion from Request
     *
     * @param Request $request
     * @param $id
     * @return Promotion
     */
    private function save(Request $request, $id) : Promotion {
        $request->validate($this->getValidations($request, $id));

        $request->merge([
            'has_mgm' => $request->has('has_mgm'),
            'is_public' => $request->has('is_public')
        ]);

        if ($request->hasFile('legal_file')&&($request->file('legal_file')->isValid())) {
            $request->merge(['legal' => $request->legal_file->storeAs('legal', $request->file('legal_file')->getClientOriginalName(), 'public')]);
        }

        $campaign = Campaign::find($request->input('campaign_id'));
        if (!empty($campaign->entry_point)) {
            $request->merge(['entry_point' => $request->input('entrypoint_id')]);
            $request->merge(['entrypoint_id' => null]);
        }

        $entrypoint_selected = $request->input('entrypoint_id');

        if (($entrypoint_selected === 'simple') || ($entrypoint_selected === 'complete')) {
            try {
                config(['druid_entrypoints.default.app' => $campaign->druid_app->selflink]);
                config(['druid_entrypoints.default.key' => $campaign->client_id . '-' . $request->input('key')]);
                config(['druid_entrypoints.default.description' => 'Promotion ' . $request->input('name')]);
                config(['druid_entrypoints.default.url' => url($request->input('key'))]);

                $entrypoint_link = \RestApi::createEntrypoints(array_merge(config('druid_entrypoints.default'), config('druid_entrypoints.' . $entrypoint_selected)));

                $entrypoint = new \Genetsis\Admin\Model\Entrypoint();
                $entrypoint->key = $campaign->client_id . '-' . $request->input('key');
                $entrypoint->name = config('druid_entrypoints.default.description');
                $entrypoint->campaign_id = $campaign->id;
                $entrypoint->ids = json_encode(config('druid_entrypoints.simple.config_id'));
                $entrypoint->fields = json_encode(config('druid_entrypoints.simple.config_field'));
                $entrypoint->selflink = $entrypoint_link;

                $entrypoint->save();
                $entrypoint_selected = $entrypoint->key;
            } catch (RestApiException $e) {
                Log::error($e->getMessage());
            }
        }

        $request->merge(['entrypoint_id' => $entrypoint_selected]);

        if ($id != null) {
            $promotion = Promotion::findOrFail($id);
            $promotion->update($request->all());
        } else {
            $promotion = Promotion::create($request->all());
        }

        try {
            $promotionType = \Genetsis\Promotions\Repositories\PromotionTypeFactory::create($promotion);
            $promotionType->save($request);
        }catch (\Exception $e) {
            Log::error('Nothing additional to save: ' . $e->getMessage());
        }

        return $promotion;
    }

    public function preview($id, $page) {
        $promotion = Promotion::findOrFail($id);
        $view = ($page == 'initial_page') ? 'promotion' : 'participar';

        // Mock Participation Info
        $participation = new Participation();
        $user = new User();
        $user->setSponsorCode('345763');
        $participation->user = $user;
        $participations = collect();

        $sponsorcode = '';
        $content = '';
        if ($tmp = $promotion->templates()->page($page)->first()){
            if (View::exists('templates.'.$tmp->template)) {
                $content = view('templates.'.$tmp->template, array_merge(json_decode($tmp->content, true)??[], compact('promotion', 'sponsorcode', 'page', 'participation','participations')))->render();
            }
        }

        return view($view, compact('promotion', 'sponsorcode', 'content', 'participation'));
    }
}
