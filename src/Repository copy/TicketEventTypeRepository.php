<?php

namespace App\Repository;

use App\Entity\TicketEventType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TicketEventType|null find($id, $lockMode = null, $lockVersion = null)
 * @method TicketEventType|null findOneBy(array $criteria, array $orderBy = null)
 * @method TicketEventType[]    findAll()
 * @method TicketEventType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketEventTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TicketEventType::class);
    }

//    /**
//     * @return TicketEventType[] Returns an array of TicketEventType objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TicketEventType
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
