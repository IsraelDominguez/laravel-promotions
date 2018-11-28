<?php

namespace Genetsis\Promotions\Models;

use Illuminate\Database\Eloquent\Model;

class Qrs extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promo_participation_qrs';

    protected $fillable = ['qr', 'promo_id', 'participation_id'];

    public $timestamps = false;

    public function promotion() {
        return $this->belongsTo(Promotion::class, 'promo_id');
    }

    public function participation() {
        return $this->belongsTo(Participation::class, 'participation_id');
    }
}
