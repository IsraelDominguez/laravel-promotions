<?php

namespace Genetsis\Promotions\Models;

use Illuminate\Database\Eloquent\Model;

class Codes extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promo_codes';

    protected $fillable = ['promo_id', 'used', 'participation_id','code', 'expires', 'win_code', 'win_code_send'];

    public $timestamps = false;

    public function promotion() {
        return $this->belongsTo(Promotion::class, 'promo_id');
    }

    public function participation() {
        return $this->belongsTo(Participation::class, 'participation_id');
    }
}
