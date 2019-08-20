<?php namespace Genetsis\Promotions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\Entrypoint
 *
 */
class Entrypoint extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promo_entrypoints';

    protected $fillable = ['key', 'campaign_id', 'name', 'ids', 'fields'];
    protected $hidden = ['deleted_at'];

    protected $primaryKey = 'key';

    public $keyType = 'string';

    public $timestamps = false;

    public $incrementing = false;


    /**
     * Get the Druid App associated with the Action
     */
    public function campaign()
    {
        return $this->hasOne(Campaign::class, 'id', 'campaign_id');
    }

    public function getFieldsAttribute($value) {
        return json_decode($value);
    }

    public function getIdsAttribute($value) {
        return json_decode($value);
    }

}
