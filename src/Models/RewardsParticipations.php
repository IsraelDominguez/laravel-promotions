<?php namespace Genetsis\Promotions\Models;

use Illuminate\Database\Eloquent\Model;

class RewardsParticipations extends Model
{
    protected $table = 'promo_rewards_participations';

    protected $fillable = ['participation_id', 'key', 'amount'];

    protected $primaryKey = ['participation_id', 'key'];
    public $incrementing = false;
    public $timestamps = false;

    public function reward() {
        return $this->hasOne(Rewards::class, 'key', 'key');
    }

    public function participation() {
        return $this->belongsTo(Participation::class, 'id', 'participation_id');
    }

}
