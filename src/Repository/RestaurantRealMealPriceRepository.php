<?php

namespace App\Repository;

use App\Entity\RestaurantRealMealPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RestaurantRealMealPrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurantRealMealPrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurantRealMealPrice[]    findAll()
 * @method RestaurantRealMealPrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantRealMealPriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RestaurantRealMealPrice::class);
    }

//    /**
//     * @return RestaurantRealMealPrice[] Returns an array of RestaurantRealMealPrice objects
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
    public function findOneBySomeField($value): ?RestaurantRealMealPrice
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
