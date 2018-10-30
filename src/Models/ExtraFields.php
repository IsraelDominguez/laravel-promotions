<?php namespace Genetsis\Promotions\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ExtraFields extends Model
{
    use LogsActivity;


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
