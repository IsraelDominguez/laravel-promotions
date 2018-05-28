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

    protected $fillable = ['used', 'participation_id'];

    public $timestamps = false;

    public function promotion() {
        return $this->belongsTo(Promotion::class, 'poromo_id');
    }

    public function participation() {
        return $this->belongsTo(Participation::class, 'participation_id');
    }
}
