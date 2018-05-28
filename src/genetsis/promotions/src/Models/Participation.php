<?php

namespace Genetsis\Promotions\Models;

use Carbon\Carbon;
use Genetsis\Promotions\Services\ExtraFieldsParticipationService;
use Illuminate\Database\Eloquent\Model;

class Participation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promo_participations';

    protected $fillable = ['user_id', 'promo_id', 'sponsor', 'origin', 'status'];


    //protected $primaryKey = ['oid', 'promocode_id'];
    //public $incrementing = false;
    public $timestamps = false;

    public function moment() {
        return $this->hasOne(Moment::class);
    }

    public function promo() {
        return $this->belongsTo(Promotion::class, 'promo_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function extraFields() {
        return $this->hasMany(ExtraFieldsParticipations::class);
    }

    public function rewards() {
        return $this->hasMany(RewardsParticipations::class);
    }

    public function codes() {
        return $this->hasOne(Codes::class);
    }


    public function getExtraFieldByKey($key) {
        return $this->extraFields->filter(function($field) use ($key){
            if ($field->key == $key){
                return $field;
            }
        })->map(function ($extraField){
            return $extraField->value;
        })->first();
    }


    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Before save a participation, check if user is consuming an extra participation to use it
        Participation::saving(function ($participation) {

        });

        static::deleting(function ($model) {
            $model->extraFields()->delete();
            $model->rewards()->delete();
        });

    }

}
