<?php

namespace Genetsis\Promotions\Models;

use Illuminate\Database\Eloquent\Model;

class PromoType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promo_type';

    /**
     * Get the Campaign record associated with the Promotion
     */
    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

}
