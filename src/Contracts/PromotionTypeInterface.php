<?php namespace Genetsis\Promotions\Contracts;

use Illuminate\Http\Request;

interface PromotionTypeInterface {

    public function save(Request $request);
}
