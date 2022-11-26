<?php

namespace App\Repository;

use App\Entity\RestaurantMatch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RestaurantMatchRepository|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurantMatchRepository|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurantMatchRepository[]    findAll()
 * @method RestaurantMatchRepository[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantMatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RestaurantMatch::class);
    }

    /**
     * @return RestaurantMatch[] Returns an array of RestaurantMatch objects
     */
    
    public function findByRoomReal($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.roomreal = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
     * @return RestaurantMatch Returns a RestaurantMatch object or null if not exist
     */
    
    public function findByMeal($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.citizen = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?RoomReal
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
