<?php

namespace Bicycle\Core;

use Bicycle\Core\Exception\NotFoundMethodException;
use Bicycle\Core\ValidatorConstraint\ConstraintFactory;
use Bicycle\Core\ValidatorConstraint\ConstraintInterface;

class EntityValidator
{
    /**
     * Validates Entity and returns array of validation errors
     *
     * @param EntityInterface $entity
     * @return array
     * @throws NotFoundMethodException
     */
    public function validate(EntityInterface $entity): array
    {
        $constraintFactory = new ConstraintFactory();
        $errors = [];
        foreach ($entity->getValidatorConstraints() as $field => $fieldValidatorConstraints) {
            foreach ($fieldValidatorConstraints as $constraintNameWithParams) {

                /** @var ConstraintInterface $validatorConstraint */
                $validatorConstraint = $constraintFactory->createConstraint($constraintNameWithParams);

                $getterMethod = sprintf('get%s', str_replace(' ', '', ucwords(str_replace('_', ' ', $field))));
                if (!method_exists($entity, $getterMethod)) {
                    throw new NotFoundMethodException(sprintf(
                            'Cannot apply %s constraint to %s. Method %s not exists',
                            $constraintNameWithParams,
                            get_class($entity),
                            $getterMethod
                        )
                    );
                }

                $error = $validatorConstraint->validate($entity->$getterMethod());

                if ($error) {
                    $errors[$field] = $error;
                }
            }
        }

        return $errors;
    }
}