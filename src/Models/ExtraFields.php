<?php namespace Genetsis\Promotions\Models;

use Illuminate\Database\Eloquent\Model;

class ExtraFields extends Model
{
    const TYPE_STRING = 'string';
    const TYPE_DATE = 'date';
    const TYPE_IMAGE = 'image';
    const TYPE_NUMBER = 'number';
    const TYPE_LINK = 'link';

    const TYPES = array(self::TYPE_STRING, self::TYPE_NUMBER, self::TYPE_IMAGE, self::TYPE_DATE, self::TYPE_LINK);

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promo_extra_fields';

    protected $fillable = ['key', 'promo_id', 'name', 'type'];

    protected $primaryKey = 'key';

    public $incrementing = false;
    public $timestamps = false;

    public function promotion() {
        return $this->belongsTo(Promotion::class);
    }
}
