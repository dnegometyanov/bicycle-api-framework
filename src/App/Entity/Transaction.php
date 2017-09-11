<?php

namespace Bicycle\App\Entity;

use Bicycle\Core\AbstractEntity;

class Transaction extends AbstractEntity
{
    const STATUS_REJECTED = 'rejected';
    const STATUS_APPROVED = 'approved';

    /**
     * @var string
     */
    private $email;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var  string
     */
    private $status;

    /**
     * @var string
     */
    private $create_date;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return Transaction
     */
    public function setEmail($email): Transaction
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     * @return Transaction
     */
    public function setAmount($amount): Transaction
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return Transaction
     */
    public function setStatus($status): Transaction
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreateDate()
    {
        return $this->create_date;
    }

    /**
     * @param mixed $create_date
     * @return Transaction
     */
    public function setCreateDate($create_date): Transaction
    {
        $this->create_date = $create_date;

        return $this;
    }

    /**
     * @return array
     */
    static function getAvailableStatuses(): array
    {
        return [
            self::STATUS_REJECTED,
            self::STATUS_APPROVED,
        ];
    }

    /**
     * @return string
     */
    public function jsonSerialize(): string
    {
        return json_encode(
            [
                'id' => $this->getId(),
                'email' => $this->getEmail(),
                'amount' => $this->getAmount(),
                'status' => $this->getStatus(),
                'create_date' => $this->getCreateDate(),
            ]
        );
    }

    public function getValidatorConstraints(): array
    {
        return [
            'email' => ['not_blank', 'email'],
            'amount' => ['not_blank'],
            'status' => ['not_blank', 'enum:rejected|approved'],
            'create_date' => ['not_blank', 'timestamp'],
        ];
    }
}