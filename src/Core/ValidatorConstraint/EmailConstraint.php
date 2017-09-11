<?php

namespace Bicycle\Core\ValidatorConstraint;

class EmailConstraint implements ConstraintInterface
{

    public function validate($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return sprintf(
                'Value %s is not a email',
                $value
            );
        }

        return null;
    }
}