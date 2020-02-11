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
    protected $exception;

    const STATUS_OK = 200;
    const STATUS_KO = 500;
    const RESULT_WIN = 'WIN';
    const RESULT_NOTWIN = 'NOT_WIN';
    const RESULT_INVALID_PINCODE = 'INVALID_PINCODE';

    public function __construct() {
    }

    /**
     * @return PromotionParticipation
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

    /**
     * @return mixed
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @param mixed $exception
     * @return ParticipationResult
     */
    public function setException($exception)
    {
        $this->exception = $exception;
        return $this;
    }


}
