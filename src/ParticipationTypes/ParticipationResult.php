<?php namespace Genetsis\Promotions\ParticipationTypes;

use Genetsis\Promotions\Contracts\PromotionParticipationInterface;

class ParticipationResult
{
    /**
     * @var PromotionParticipationInterface $participation
     */
    protected $participation;
    protected $status;
    protected $result;
    protected $message;

    const STATUS_OK = 200;
    const STATUS_KO = 500;
    const RESULT_WIN = 'WIN';
    const RESULT_NOTWIN = 'NOT_WIN';

    public function __construct() {
    }

    public static function i() {
        return new ParticipationResult();
    }

    /**
     * @return mixed
     */
    public function getParticipation()
    {
        return $this->participation;
    }

    /**
     * @param PromotionParticipation $participation
     * @return ParticipationResult
     */
    public function setParticipation($participation)
    {
        $this->participation = $participation;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return ParticipationResult
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     * @return ParticipationResult
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     * @return ParticipationResult
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }


}
