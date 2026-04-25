<?php

namespace App\EventListener;

use App\Repository\CoordinatorRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{
    public function __construct(private CoordinatorRepository $repository) {}

    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $user = $event->getUser();
        $payload = $event->getData();
        $coordinator = $this->repository->findOneBy(['email' => $user->getUserIdentifier()]);

        if ($coordinator) {
            $payload['name'] = $coordinator->getName();
        }

        $event->setData($payload);
    }
}
