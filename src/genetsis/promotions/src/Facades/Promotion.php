<?php namespace Genetsis\Promotions\Facades;

use Illuminate\Support\Facades\Facade;

class PromotionFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Promotion';
    }
}