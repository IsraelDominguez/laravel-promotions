<?php namespace Genetsis\Promotions\Controllers;

use Genetsis\Admin\Controllers\AdminController;
use Genetsis\Promotions\Models\Campaign;
use Genetsis\Promotions\Models\Entrypoint;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use JMS\Serializer\Tests\Fixtures\Log;

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
        $campaigns = Campaign::latest()->paginate(10);
        return view('promotion::campaigns.index',compact('campaigns'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('promotion::campaigns.create');
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
            'key' => 'required|alpha_dash|max:50',
            'entry_point' => 'nullable|alpha_dash|max:100',
            'name' => 'unique:promo_campaign|required',
            'ends' => 'nullable|after:starts'
        ]);

        $campaign = Campaign::create($request->all());

        try {
            $druid_app = \RestApi::searchAppsBy(['key'=>$campaign->client_id]);
            $campaign->selflink = $druid_app->getUri();
            $campaign->update();
        } catch (\Exception $e) {
            \Log::debug('Error: ' . $e->getMessage());
        }

        $this->getDruidEntrypoints($campaign);

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
        $campaign = Campaign::find($id);

        return view('promotion::campaigns.edit',compact('campaign'));
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
            'key' => 'required|alpha_dash|max:50',
            'entry_point' => 'nullable|alpha_dash|max:100',
            'name' => ['required',
                        Rule::unique('promo_campaign')->ignore($id)
            ],
            'ends' => 'nullable|after:starts'
        ]);

        $campaign = Campaign::find($id);

        try {
            $druid_app = \RestApi::searchAppsBy(['key'=>$campaign->client_id]);
            $campaign->selflink = $druid_app->getUri();
        } catch (\Exception $e) {
            \Log::debug('Error: ' . $e->getMessage());
        }

        $campaign->update($request->all());

        $this->getDruidEntrypoints($campaign);

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

            $fields = collect($druid_entrypoint->getConfigField())->map(function ($field) {
                return $field->getField()->getKey();
            })->toJson();

            $ids = collect($druid_entrypoint->getConfigId())->map(function ($idfield) {
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
