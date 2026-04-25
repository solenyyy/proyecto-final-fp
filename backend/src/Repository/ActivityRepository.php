<?php

namespace App\Repository;

use App\Entity\Activity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    public function findAllOrderedByProximity(): array
    {
        $now = new \DateTime();

        $upcoming = $this->createQueryBuilder('a')
            ->where('a.startDate >= :now')
            ->setParameter('now', $now)
            ->orderBy('a.startDate', 'ASC')
            ->getQuery()
            ->getResult();

        $past = $this->createQueryBuilder('a')
            ->where('a.startDate < :now')
            ->setParameter('now', $now)
            ->orderBy('a.startDate', 'DESC')
            ->getQuery()
            ->getResult();

        return array_merge($upcoming, $past);
    }
}
