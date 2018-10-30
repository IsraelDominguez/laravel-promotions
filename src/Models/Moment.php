<?php

namespace Genetsis\Promotions\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Moment extends Model
{
    use LogsActivity;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promo_moments';

    protected $fillable = ['used', 'code_send', 'participation_id'];

    public $timestamps = false;

    public function promotion() {
        return $this->belongsTo(Promotion::class, 'promo_id');
    }

    public function participation() {
        return $this->belongsTo(Participation::class, 'participation_id');
    }
}
