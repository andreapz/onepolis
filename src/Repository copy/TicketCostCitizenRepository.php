<?php

namespace App\Repository;

use App\Entity\TicketCostCitizen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TicketCostCitizen|null find($id, $lockMode = null, $lockVersion = null)
 * @method TicketCostCitizen|null findOneBy(array $criteria, array $orderBy = null)
 * @method TicketCostCitizen[]    findAll()
 * @method TicketCostCitizen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketCostCitizenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TicketCostCitizen::class);
    }

    /**
    * @return TicketCostCitizen[] Returns an array of TicketCostCitizen objects
    */   
    public function findByEvent($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.event = :query')
            ->setParameter('query', $value)
            ->getQuery()
            ->getResult()
        ;
    }
}
