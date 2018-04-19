<?php namespace Genetsis\Promotions\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Genetsis\Promotions\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'unique:promo_campaign|required',
            'ends' => 'nullable|after:starts'
        ]);

        Campaign::create($request->all());
        return redirect()->route('campaigns.home')
            ->with('success','Campain created successfully');
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => ['required',
                        Rule::unique('promo_campaign')->ignore($id)
            ],
            'ends' => 'nullable|after:starts'
        ]);

        Campaign::find($id)->update($request->all());
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

}
