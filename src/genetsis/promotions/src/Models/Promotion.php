<?php

namespace Genetsis\Promotions\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promo';

    protected $fillable = ['name', 'starts', 'ends', 'campaign_id', 'type_id', 'max_user_participations', 'max_user_participations_by_day'];

//    protected $dateFormat = 'Y-m-d H:i';
//
//    protected $dates = [
//        'starts',
//        'ends'
//    ];

    /**
     * Get the Campaign record associated with the Promotion
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function participations() {
        return $this->hasMany(Participation::class, 'promo_id');
    }

    public function type(){
        return $this->belongsTo(PromoType::class);
    }

    public function extra_fields() {
        return $this->hasMany(ExtraFields::class, 'promo_id');
    }

    public function rewards() {
        return $this->hasMany(Rewards::class, 'promo_id');
    }

    public function codes() {
        return $this->belongsTo(Codes::class);
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        Promotion::deleting(function ($model) {
            $model->extra_fields()->delete();
            $model->rewards()->delete();
            $model->codes()->delete();
        });
    }

}
