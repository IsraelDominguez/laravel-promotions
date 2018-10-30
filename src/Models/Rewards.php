<?php namespace Genetsis\Promotions\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Rewards extends Model
{
    use LogsActivity;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promo_rewards';

    protected $fillable = ['key', 'name', 'promo_id', 'stock'];

    protected $primaryKey = 'key';

    public $incrementing = false;
    public $timestamps = false;

    public function promotion() {
        return $this->belongsTo(Promotion::class);
    }
}
