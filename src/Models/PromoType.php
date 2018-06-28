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

    const PINCODE_TYPE = 'pincode';
    const SORTEO_TYPE = 'sorteo';
    const MOMENT_TYPE = 'win-moment';

    /**
     * Get the Campaign record associated with the Promotion
     */
    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

}
