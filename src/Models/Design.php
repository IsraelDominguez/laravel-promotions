<?php namespace Genetsis\Promotions\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Design  extends Model
{
    use LogsActivity;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promo_design';
    protected $primaryKey = 'promo_id';
    public $incrementing = false;

    protected $fillable = ['background_image', 'background_color'];

    public $timestamps = false;

    public function promotion() {
        return $this->belongsTo(Promotion::class, 'promo_id');
    }
}
