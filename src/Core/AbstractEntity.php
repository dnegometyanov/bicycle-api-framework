<?php

namespace Bicycle\Core;

abstract class AbstractEntity implements EntityInterface, \JsonSerializable
{
    private $id;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    abstract function jsonSerialize(): string ;

    abstract function getValidatorConstraints(): array;
}
