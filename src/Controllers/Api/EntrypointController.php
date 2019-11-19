<?php namespace Genetsis\Promotions\Controllers\Api;

use Genetsis\Admin\Models\Entrypoint;
use Genetsis\Promotions\Models\Campaign;

class EntrypointController extends ApiController
{

    /**
     * Get all Entry Points for a DruidApp
     * @param $id string app_id
     * @return \Illuminate\Http\Response
     */
    public function get($id) {
        try {
            $campaign = Campaign::findOrFail($id);

            $entrypoints = Entrypoint::where('client_id', $campaign->druid_app->client_id)->get();
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage());
        }
        return $this->sendResponse($entrypoints, 'Entrypoints');
    }
}
