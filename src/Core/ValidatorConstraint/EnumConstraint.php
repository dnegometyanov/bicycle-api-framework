<?php

namespace Bicycle\Core\ValidatorConstraint;

class EnumConstraint implements ConstraintInterface
{

    /**
     * @var array
     */
    protected $enums;

    public function __construct(array $enums)
    {
        $this->enums = $enums;
    }

    public function validate($value)
    {
        if (!in_array($value, $this->enums)) {
            return sprintf(
                'Value %s does not matches allowed enums %s',
                $value,
                implode('|', $this->enums)
            );
        }

        return null;
    }
}