<?php

namespace Bicycle\Core\ValidatorConstraint;

class ConstraintFactory
{
    public function createConstraint($constraintNameWithParams)
    {
        $constraintParts = explode(':', $constraintNameWithParams);
        $constraintNameWithParams = $constraintParts[0];

        switch ($constraintNameWithParams) {
            case 'not_blank':
                return new NotBlankConstraint();
                break;
            case 'email':
                return new EmailConstraint();
                break;
            case 'timestamp':
                return new TimestampConstraint();
                break;
            case 'enum':
                $emums = explode('|', $constraintParts[1]);
                return new EnumConstraint($emums);
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Constraint type %s not supported', $constraintNameWithParams));
        }
    }
}