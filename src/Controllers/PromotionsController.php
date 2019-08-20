<?php namespace Genetsis\Promotions\Controllers;

use Genetsis\Admin\Controllers\AdminController;
use Carbon\Carbon;

use Genetsis\Promotions\Models\Campaign;
use Genetsis\Promotions\Models\Entrypoint;
use Genetsis\Promotions\Models\ExtraFields;
use Genetsis\Promotions\Models\Promotion;
use Genetsis\Promotions\Models\PromoType;

use Genetsis\Promotions\Models\Rewards;
use Genetsis\Promotions\PromotionTypes\PromotionTypeFactory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $promotions = Promotion::latest()->paginate(10);
        return view('promotion::promotions.index',compact('promotions'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
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
        if ($request->ajax()) {
            $promotions = Promotion::latest()->with('campaign','type');

            return DataTables::of($promotions)
                ->addColumn('participations', function($promotion) {
                    return count($promotion->participations);
                })
                ->addColumn('options', function ($promotion) {
                    return '
                        <div class="actions" style="width:64px">
                        <a class="actions__item zmdi zmdi-eye" href="' . route('promotions.show', $promotion->id) . '"></a>
                        <a class="actions__item zmdi zmdi-edit" href="' . route('promotions.edit', $promotion->id) . '"></a>
                        </div>                        
                        ';
                })
                ->addColumn('delete', function ($promotion) {
                    return '
                        <div class="actions">                                                
                        <a class="actions__item zmdi zmdi-delete del" data-id="' . $promotion->id . '"></a>
                        </div>                        
                        ';
                })
                ->rawColumns(['options', 'delete'])
                ->make(true);
        }
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
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Genetsis\Promotions\Exceptions\PromotionException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->save($request, null);

        return redirect()->route('promotions.home')
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
        $this->save($request, $id);

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
        //TODO: delete all files asociated (images, legal,...)

        Promotion::find($id)->delete();
        return redirect()->route('promotions.home')
            ->with('success','Promotion deleted successfully');
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
            'name' => 'required|unique:promo|max:50',
            'campaign_id' => ['required','integer'],
            'type_id' => 'required|integer',
            'max_user_participations' => 'nullable|digits_between:1,99',
            'max_user_participations_by_day' => 'nullable|digits_between:1,99',
            'starts' => 'required',
            'ends' => 'nullable|after:starts',
            'key' => 'required|unique:promo|alpha_dash|max:50',
            'entrypoint_id' => 'nullable|alpha_dash|max:200',
            'has_mgm' => 'nullable',
            'legal' => 'nullable|max:100',
            'legal_file' => 'nullable|file|mimes:pdf',
            'pack' => 'nullable|alpha_num|max:100',
            'pack_key' => 'nullable|alpha_dash|max:100',
            'pack_name' => 'nullable|max:100',
            'pack_max' => 'nullable|integer',
            'win_moment_file' => 'nullable',
            'pincodes_file' => 'nullable'
        ];

        // Edit Promotion
        if ($id != null) {
            $validations['name'] = ['required', Rule::unique('promo')->ignore($id), 'max:50'];
            $validations['key'] = ['required', Rule::unique('promo')->ignore($id),'alpha_dash', 'max:50'];
        }

        if (config('promotion.front_templates_enabled')) {
            $validations['title'] = 'required';

            if ($request->has('has_mgm')) {
                $validations['facebook'] = 'required';
                $validations['twitter'] = 'required';
                $validations['whatsapp'] = 'required';
            }
        }

        return $validations;
    }

    /**
     * Save or Update Promotion from Request
     *
     * @param Request $request
     * @param $id
     */
    private function save(Request $request, $id) : void {
        $request->validate($this->getValidations($request, $id));

        $request->merge(array('has_mgm' => $request->has('has_mgm')));

        if ($request->hasFile('legal_file')&&($request->file('legal_file')->isValid())) {
            $request->merge(array('legal' => $request->legal_file->storeAs('legal', $request->file('legal_file')->getClientOriginalName(), 'public')));
        }

        if ($id != null) {
            $promotion = Promotion::findOrFail($id);
            $promotion->update($request->all());
        } else {
            $promotion = Promotion::create($request->all());
        }

        try {
            $promotionType = PromotionTypeFactory::create($promotion);
            $promotionType->save($request);
        }catch (\Exception $e) {
            Log::info('Nothing additional to save');
        }
    }
}
