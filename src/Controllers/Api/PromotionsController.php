<?php namespace Genetsis\Promotions\Controllers\Api;

use Genetsis\Promotions\Models\Participation;
use Genetsis\Promotions\Models\Promotion;
use Genetsis\Promotions\Models\User;
use Illuminate\Http\Request;

class PromotionsController extends ApiController
{

    public function winners(int $promotion_id, Request $request) {
        try {
            Participation::where('promo_id', $promotion_id)->whereIn('winner', [Participation::IS_WINNER, Participation::IS_RESERVE])->update(['winner'=>null]);

            $winners = Participation::where('promo_id', $promotion_id)->inRandomOrder()->limit($request->input('winners'))->get();
            $winners->each(function($user) {
                $user->winner = Participation::IS_WINNER;
                $user->save();
            });

            $reserves = Participation::where('promo_id', $promotion_id)->inRandomOrder()->limit($request->input('reserves'))->get();
            $reserves->each(function($user) {
                $user->update(['winner' => Participation::IS_RESERVE]);
            });

        } catch (\InvalidArgumentException $e) {
            return $this->sendError($e->getMessage(), 'Winners Error', 200);
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage());
        }
        return $this->sendResponse(compact('winners', 'reserves'), 'Winners');
    }
}
