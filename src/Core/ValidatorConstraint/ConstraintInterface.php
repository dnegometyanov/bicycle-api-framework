<?php
namespace Bicycle\Core\ValidatorConstraint;

interface ConstraintInterface
{
    public function validate($value);
}