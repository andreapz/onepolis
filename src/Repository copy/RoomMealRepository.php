<?php

namespace App\Repository;

use App\Entity\RoomMeal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RoomMeal|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomMeal|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomMeal[]    findAll()
 * @method RoomMeal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomMealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomMeal::class);
    }
    
    /**
     * @return RoomMeal[] Returns an array of RoomMeal objects
     */
    public function findSelected($event)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.event = :event')
            ->setParameter('event', $event)
            ->getQuery()
            ->getResult()
        ;
    }
    
    /*
    public function findOneBySomeField($value): ?RoomMeal
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
