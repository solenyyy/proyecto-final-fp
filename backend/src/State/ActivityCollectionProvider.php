<?php

namespace App\State;

use App\Repository\ActivityRepository;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;

class ActivityCollectionProvider implements ProviderInterface
{
    public function __construct(private ActivityRepository $repository) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        return $this->repository->findAllOrderedByProximity();
    }
}
