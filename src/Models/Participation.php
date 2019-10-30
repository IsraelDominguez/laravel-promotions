<?php

namespace Genetsis\Promotions\Models;

use Illuminate\Database\Eloquent\Model;

class Participation extends Model
{

    const IS_WINNER = 'winner';
    const IS_RESERVE = 'reserve';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promo_participations';

    protected $fillable = ['user_id', 'promo_id', 'sponsor', 'origin', 'status', 'winner'];

    protected $dates = ['date'];

    public $timestamps = false;

    public function promo() {
        return $this->belongsTo(Promotion::class, 'promo_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function extraFields() {
        return $this->hasMany(ExtraFieldsParticipations::class, 'participation_id', 'id');
    }

    public function rewards() {
        return $this->hasMany(RewardsParticipations::class, 'participation_id', 'id');
    }

    public function code() {
        return $this->hasOne(Codes::class, 'participation_id', 'id');
    }

    public function moment() {
        return $this->hasOne(Moment::class, 'participation_id', 'id');
    }

    public function qr() {
        return $this->hasOne(Qrs::class, 'participation_id', 'id');
    }


    /**
     * Show Date participation in promotion TimeZone
     *
     * @param $value
     * @return string
     */
    public function getDateAttribute($value)
    {
        return $this->asDateTime($value)->timezone(config('promotion.timezone'))->toDateTimeString();
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
     * Scope a query to only include winner or reserve participations
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWinner($query, string $winner) {
        return $query->where('winner', $winner);
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
            $model->qr()->delete();

            if ($model->code != null) {
                $model->code->participation_id = null;
                $model->code->used = null;
                $model->code->save();
            }

            if ($model->moment != null) {
                $model->moment->participation_id = null;
                $model->moment->used = null;
                $model->moment->code_send = null;
                $model->moment->save();
            }
        });

    }

}
