<?php

namespace Genetsis\Promotions\Models;

use Illuminate\Database\Eloquent\Model;

class QrsPack extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promo_packs_qrs';

    protected $fillable = ['pack', 'key', 'name', 'max', 'promo_id'];

    public $timestamps = false;

    public function promotion() {
        return $this->belongsTo(Promotion::class, 'promo_id');
    }
}
