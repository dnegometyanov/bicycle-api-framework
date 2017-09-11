<?php

namespace Bicycle\Core\ValidatorConstraint;

class NotBlankConstraint implements ConstraintInterface
{
    public function validate($value)
    {
        if (empty($value)) {
            return sprintf(
                'Value is empty',
                $value
            );
        }

        return null;
    }
}