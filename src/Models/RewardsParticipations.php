<?php namespace Genetsis\Promotions\Models;

use Illuminate\Database\Eloquent\Model;

class RewardsParticipations extends Model
{
    use LogsActivity;

    protected $table = 'promo_rewards_participations';

    protected $fillable = ['participation_id', 'key', 'amount'];

    protected $primaryKey = ['participation_id', 'key'];
    public $incrementing = false;
    public $timestamps = false;

}
