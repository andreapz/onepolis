<?php

namespace App\Repository;

use App\Entity\RestaurantCostCitizen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RestaurantCostCitizen|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurantCostCitizen|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurantCostCitizen[]    findAll()
 * @method RestaurantCostCitizen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantCostCitizenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RestaurantCostCitizen::class);
    }

    public function findByCitizenAndEvent($citizen, $event)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.event = :event')
            ->andWhere('t.citizen = :citizen')
            ->setParameter('event', $event)
            ->setParameter('citizen', $citizen)
            ->getQuery()
            ->getResult()
        ;
    }
    
    
//    /**
//     * @return RestaurantCostCitizen[] Returns an array of RestaurantCostCitizen objects
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
    public function findOneBySomeField($value): ?RestaurantCostCitizen
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
