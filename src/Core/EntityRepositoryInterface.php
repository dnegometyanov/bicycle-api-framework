<?php

namespace Bicycle\Core;

interface EntityRepositoryInterface
{
    public function getEntityClass(): string;

    public function find(integer $id): array;

    public function getValidatorConstraints(): array;
}