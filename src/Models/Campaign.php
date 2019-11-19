<?php

namespace Genetsis\Promotions\Models;

use Genetsis\Admin\Models\DruidApp;
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

    protected $fillable = ['name', 'starts', 'ends', 'key', 'entry_point', 'client_id'];

    protected $encryptable = ['secret'];

    /**
     * Get the promotions for a campaign.
     */
    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function druid_app(){
        return $this->hasOne(DruidApp::class, 'client_id', 'client_id');
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
