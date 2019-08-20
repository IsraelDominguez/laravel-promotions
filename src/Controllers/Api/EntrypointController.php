<?php namespace Genetsis\Promotions\Controllers\Api;

use Genetsis\Promotions\Models\Entrypoint;

class EntrypointController extends ApiController
{

    /**
     * Get all Entry Points for a DruidApp
     * @param $id string app_id
     * @return \Illuminate\Http\Response
     */
    public function get($id) {
        try {
            $entrypoints = Entrypoint::where('campaign_id', $id)->get();
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage());
        }
        return $this->sendResponse($entrypoints, 'Entrypoints');
    }
}
