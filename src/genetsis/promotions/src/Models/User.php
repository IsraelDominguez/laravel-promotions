<?php

namespace Genetsis\Promotions\Models;

use Genetsis\Promotions\Contracts\PromoUserEmailInterface;
use Genetsis\Promotions\Contracts\PromoUserInterface;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements PromoUserInterface, PromoUserEmailInterface
{
    protected $photo = '';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promo_users';

    protected $fillable = ['id', 'email', 'name', 'sponsor_code'];

    protected $primaryKey = 'id';
    public $incrementing = false;

    /**
     * Get the Partipations related to user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function participations()
    {
        return $this->hasMany(Participation::class)
            ->withPivot('date');
    }

    public function extraParticipations()
    {
        return $this->belongsToMany(ExtraParticipation::class);
    }


    /**
     * Instancia static to Promo Users
     * @return EventData
     */
    public static function i(){
        return new User();
    }


    public function setId($value)
    {
        $this->attributes['id'] = $value;
        return $this;
    }

    public function setEmail($value)
    {
        $this->attributes['email'] = $value;
        return $this;
    }

    public function setName($value)
    {
        $this->attributes['name'] = $value;
        return $this;
    }

    public function setSponsorCode($value)
    {
        $this->attributes['sponsor_code'] = $value;
        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getSponsorCode() {
        return $this->sponsor_code;
    }

    public function getPhoto()
    {
        return $this->photo;
    }
    
    public function setPhoto($photo)
    {
        $this->photo = $photo;
        return $this;
    }

}
