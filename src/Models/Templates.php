<?php namespace Genetsis\Promotions\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Templates extends Model
{
    use LogsActivity;

    const TEMPLATE_LEFT = 'left';
    const TEMPLATE_RIGHT = 'right';

    const TEMPLATES = array(self::TEMPLATE_LEFT, self::TEMPLATE_RIGHT);

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promo_templates';

    protected $fillable = ['promo_id', 'page', 'template', 'content'];

    public $timestamps = false;

    public function promotion() {
        return $this->belongsTo(Promotion::class, 'promo_id');
    }

    /**
     * Scope to query template by page
     * @param $query
     * @return mixed
     */
    public function scopePage($query, $page) {
        return $query->where('page', $page);
    }

}
