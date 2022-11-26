<?php

namespace App\Repository;

use App\Entity\RoomCost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RoomCost|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomCost|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomCost[]    findAll()
 * @method RoomCost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomCostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomCost::class);
    }

//    /**
//     * @return RoomCost[] Returns an array of RoomCost objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RoomCost
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
