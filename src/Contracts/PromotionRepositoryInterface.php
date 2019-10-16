<?php namespace Genetsis\Promotions\Contracts;

use Illuminate\Http\Request;

interface PromotionRepositoryInterface {

    public function save(Request $request);
}
