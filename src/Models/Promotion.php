<?php

namespace Genetsis\Promotions\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promo';

    protected $fillable = ['name', 'starts', 'ends', 'campaign_id', 'type_id', 'max_user_participations', 'max_user_participations_by_day', 'key', 'has_mgm', 'entrypoint_id', 'legal'];

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
        return $this->hasMany(Codes::class, 'promo_id');
    }

    public function moment() {
        return $this->hasMany(Moment::class, 'promo_id');
    }

    public function qrspack() {
        return $this->hasOne(QrsPack::class, 'promo_id');
    }

    public function entrypoint() {
        return $this->hasOne(Entrypoint::class, 'key', 'entrypoint_id');
    }

    public function seo() {
        return $this->hasOne(Seo::class, 'promo_id', 'id');
    }

    public function design() {
        return $this->hasOne(Design::class, 'promo_id', 'id');
    }

    public function templates() {
        return $this->hasMany(Templates::class, 'promo_id');
    }

    public function winners() {
        return $this->hasMany(Participation::class, 'promo_id')->winner(Participation::IS_WINNER);
        //return $this->participations()->winner(Participation::IS_WINNER);
    }

    public function reserves() {
        return $this->hasMany(Participation::class, 'promo_id')->winner(Participation::IS_RESERVE);
    }

    public function scopeIsActive() {
        if ($this->ends) {
            return Carbon::now()->between(Carbon::createFromFormat('Y-m-d H:i:s', $this->starts), Carbon::createFromFormat('Y-m-d H:i:s', $this->ends));
        } else {
            return Carbon::now()->greaterThan(Carbon::createFromFormat('Y-m-d H:i:s', $this->starts));
        }
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
            $model->moment()->delete();
            $model->qrspack()->delete();
            $model->seo()->delete();
            $model->design()->delete();
            $model->templates()->delete();

            foreach ($model->participations as $participation) {
                $participation->delete();
            }
        });
    }

}
