<?php namespace Genetsis\Promotions\Models;

use Illuminate\Database\Eloquent\Model;

class ExtraFields extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promo_extra_fields';

    protected $fillable = ['key', 'promo_id', 'name'];

    protected $primaryKey = 'key';

    public $incrementing = false;
    public $timestamps = false;

    public function promotion() {
        return $this->belongsTo(Promotion::class);
    }
}
