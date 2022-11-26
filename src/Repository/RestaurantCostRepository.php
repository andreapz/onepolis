<?php

namespace App\Repository;

use App\Entity\RestaurantCost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RestaurantCost|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurantCost|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurantCost[]    findAll()
 * @method RestaurantCost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantCostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RestaurantCost::class);
    }

//    /**
//     * @return RestaurantCost[] Returns an array of RestaurantCost objects
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
    public function findOneBySomeField($value): ?RestaurantCost
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
