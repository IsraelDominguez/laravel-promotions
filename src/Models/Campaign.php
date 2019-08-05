<?php

namespace Genetsis\Promotions\Models;

use Genetsis\Promotions\Traits\Encryptable;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use Encryptable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promo_campaign';

    protected $fillable = ['name', 'starts', 'ends', 'key', 'entry_point', 'client_id', 'secret'];

    protected $encryptable = ['secret'];

    /**
     * Get the promotions for a campaign.
     */
    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

    /**
     * Get the Entrypoints associated to this Druid App
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entrypoints(){
        return $this->hasMany(Entrypoint::class, 'campaign_id', 'id');
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
