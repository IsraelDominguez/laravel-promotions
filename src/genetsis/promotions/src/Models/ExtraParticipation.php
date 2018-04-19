<?php

namespace Genetsis\Promotions\Models;

use Illuminate\Database\Eloquent\Model;

class ExtraParticipation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promo_extra_participations';

    protected $fillable = ['user_id', 'promo_id', 'sponsor', 'origin'];

    public $timestamps = false;

    public function user() {
        return $this->belongsTo(User::class);
    }
}
