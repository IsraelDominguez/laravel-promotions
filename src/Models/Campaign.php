<?php

namespace Genetsis\Promotions\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promo_campaign';

    protected $fillable = ['name', 'starts', 'ends', 'key', 'entry_point'];

    /**
     * Get the promotions for a campaign.
     */
    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }


    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        Campaign::deleting(function ($model) {
            $model->promotions()->delete();
        });
    }

}
