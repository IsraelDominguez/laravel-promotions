<?php

namespace Genetsis\Promotions\Models;

use Illuminate\Database\Eloquent\Model;

class Moment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promo_moments';

    protected $fillable = ['used', 'code_send', 'participation_id'];

    public $timestamps = false;

    public function participation() {
        return $this->belongsTo(Participation::class);
    }
}
