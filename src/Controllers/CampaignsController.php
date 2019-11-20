<?php namespace Genetsis\Promotions\Controllers;

use Genetsis\Admin\Controllers\AdminController;
use Genetsis\Admin\Models\DruidApp;
use Genetsis\Promotions\Models\Campaign;
use Genetsis\Promotions\Models\Entrypoint;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class CampaignsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        \View::share('section', 'campaign');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('promotion::campaigns.index');
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
        $campaigns = Campaign::with('druid_app');

        return \datatables()->eloquent($campaigns)
            ->addColumn('druid_app', function($campaign) {
                return $campaign->druid_app->client_id . ' - ' . $campaign->druid_app->name;
            })
            ->addColumn('options', function ($campaign) {
                return '
                    <div class="actions" style="width:65px">
                    <a class="actions__item zmdi zmdi-eye" href="' . route('campaigns.show', $campaign->id) . '"></a>
                    <a class="actions__item zmdi zmdi-edit" href="' . route('campaigns.edit', $campaign->id) . '"></a>                    
                    </div>                        
                    ';
            })
            ->addColumn('delete', 'promotion::partials.deletetable')
            ->rawColumns(['options', 'delete'])
            ->make(true);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $druid_apps = DruidApp::all();

        return view('promotion::campaigns.create', compact('druid_apps'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'key' => 'required|unique:promo_campaign|alpha_dash|max:250',
            'entry_point' => 'nullable|alpha_dash|max:100',
            'name' => 'unique:promo_campaign|required',
            'ends' => 'nullable|after:starts',
            'client_id' => 'nullable|exists:druid_apps,client_id'
        ]);

        $campaign = Campaign::create($request->all());

        return redirect()->route('campaigns.home')
            ->with('success','Campaign created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $campaign = Campaign::find($id);
        return view('promotion::campaigns.show',compact('campaign'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $druid_apps = DruidApp::all();
        $campaign = Campaign::find($id);

        return view('promotion::campaigns.edit',compact('campaign', 'druid_apps'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'key' => ['required', 'alpha_dash', 'max:250',
                Rule::unique('promo_campaign')->ignore($id)],
            'entry_point' => 'nullable|alpha_dash|max:200',
            'name' => ['required',
                        Rule::unique('promo_campaign')->ignore($id)
            ],
            'ends' => 'nullable|after:starts',
            'client_id' => 'nullable|exists:druid_apps,client_id'
        ]);

        $campaign = Campaign::findOrFail($id);

        $campaign->update($request->all());

        return redirect()->route('campaigns.home')
            ->with('success','Campaign updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Campaign::find($id)->delete();
        return redirect()->route('campaigns.home')
            ->with('success','Campaign deleted successfully');
    }

    /**
     * Refresh App Entrypoints from Druid
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function refresh($id)
    {
        $campaign = Campaign::findOrFail($id);

        $this->getDruidEntrypoints($campaign);

        return redirect()->route('campaigns.edit', $id)
            ->with('success','Entrypoints refresh successfully');
    }

    /**
     * Get Druid Entrypoints for a Campaign
     *
     * @param Campaign $campaign
     */
    private function getDruidEntrypoints(Campaign $campaign)
    {
        $druid_entrypoints = \RestApi::searchEntrypointsBy(['app' => $campaign->client_id]);

        $entrypoints = [];

        foreach ($druid_entrypoints->getResources('entrypoints') as $druid_entrypoint) {

            $fields = collect($druid_entrypoint->getConfigField())->filter(function($field) {
                return $field->getField() != null;
            })->map(function ($field) {
                return $field->getField()->getKey();
            })->toJson();

            $ids = collect($druid_entrypoint->getConfigId())->filter(function($field) {
                return $field->getField() != null;
            })->map(function ($idfield) {
                return $idfield->getField()->getName();
            })->toJson();

            $entrypoint = new Entrypoint();
            $entrypoint->key = $druid_entrypoint->getKey();
            $entrypoint->name = $druid_entrypoint->getDescription();
            $entrypoint->ids = $ids;
            $entrypoint->fields = $fields;

            array_push($entrypoints, $entrypoint);
        }


        $campaign->entrypoints()->each(function ($relation) use ($entrypoints) {
            if (!collect($entrypoints)->contains('key', $relation->key)) {
                return $relation->delete();
            }
        });

        collect($entrypoints)->map(function ($entrypoint) use ($campaign) {
            $campaign->entrypoints()->updateOrCreate(
                ['key' => $entrypoint->key],
                [
                    'name' => $entrypoint->name,
                    'ids' => json_encode($entrypoint->ids),
                    'fields' => json_encode($entrypoint->fields)
                ]
            );
        });
    }


}
