<?php namespace Genetsis\Promotions\ParticipationTypes;

use Genetsis\Promotions\Contracts\AfterFilterParticipationInterface;
use Genetsis\Promotions\Contracts\BeforeFilterParticipationInterface;
use Genetsis\Promotions\Contracts\FilterParticipationInterface;
use Genetsis\Promotions\Contracts\PromotionParticipationInterface;
use Genetsis\Promotions\Models\Participation;

abstract class PromotionParticipation extends Participation implements AfterFilterParticipationInterface, BeforeFilterParticipationInterface
{
    protected $filter_participation;

    protected $extra_fields;

    protected $rewards;

    public function __construct()
    {
    }

    public function before(PromotionParticipationInterface $participation) {
        if ($this->filter_participation instanceof BeforeFilterParticipationInterface) {
            $this->filter_participation->before($participation);
        }
    }

    public function after(PromotionParticipationInterface $participation)
    {
        if ($this->filter_participation instanceof AfterFilterParticipationInterface) {
            $this->filter_participation->after($participation);
        }
    }

    public function setUserId($value)
    {
        $this->attributes['user_id'] = $value;
        return $this;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function setSponsor($value)
    {
        $this->attributes['sponsor'] = $value;
        return $this;
    }

    public function getSponsor() {
        return $this->sponsor;
    }

    public function setPromoId($value)
    {
        $this->attributes['promo_id'] = $value;
        return $this;
    }

    public function getPromoId() {
        return $this->promo_id;
    }

    public function setOrigin($value)
    {
        $this->attributes['origin'] = $value;
        return $this;
    }

    public function getOrigin() {
        return $this->origin;
    }

    public function setStatus($value) {
        $this->attributes['status'] = $value;
        return $this;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setExtraFields($key, $value) {
        if ($this->extra_fields == null) {
            $this->extra_fields = collect([]);
        }

        $this->extra_fields->put($key, $value);
        return $this;
    }

    public function getExtraFields() {
        return $this->extra_fields;
    }

    public function setRewards($key, $amount = 1) {
        if ($this->rewards == null) {
            $this->rewards = collect([]);
        }

        $this->rewards->put($key, $amount);
        return $this;
    }

    public function getRewards() {
        return $this->rewards;
    }

    /**
     * @return mixed
     */
    public function getFilterParticipation()
    {
        return $this->filter_participation;
    }

    /**
     * @param mixed $filter_participation
     * @return PromotionParticipation
     */
    public function setFilterParticipation(FilterParticipationInterface $filter_participation)
    {
        $this->filter_participation = $filter_participation;
        return $this;
    }


}