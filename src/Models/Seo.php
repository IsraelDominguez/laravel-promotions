<?php namespace Genetsis\Promotions\Models;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promo_seo';
    protected $primaryKey = 'promo_id';
    public $incrementing = false;

    protected $fillable = ['title', 'facebook', 'twitter', 'whatsapp'];

    public $timestamps = false;

    public function promotion() {
        return $this->belongsTo(Promotion::class, 'promo_id');
    }
}
