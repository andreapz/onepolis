<?php

namespace App\Repository;

use App\Entity\RestaurantExtraCost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RestaurantExtraCost|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurantExtraCost|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurantExtraCost[]    findAll()
 * @method RestaurantExtraCost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantExtraCostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RestaurantExtraCost::class);
    }

//    /**
//     * @return RestaurantExtraCost[] Returns an array of RestaurantExtraCost objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RestaurantExtraCost
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
