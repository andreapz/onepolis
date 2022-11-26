<?php

namespace App\Repository;

use App\Entity\RestaurantMeal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RestaurantMeal|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurantMeal|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurantMeal[]    findAll()
 * @method RestaurantMeal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantMealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RestaurantMeal::class);
    }

    /**
     * @return RestaurantMeal[] Returns an array of RestaurantMeal objects
     */
    
    public function findByRestaurant($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.restaurant = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    
    /**
     * @return RestaurantMeal[] Returns an array of RestaurantMeal objects
     */
    
    public function findCountByEvent($event)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT rm.id, rm.total, rx.rcount FROM (SELECT * FROM restaurant_meal rm WHERE eid = :event) rm 
                    LEFT JOIN (SELECT j.mid mid, (SELECT COUNT(*) FROM restaurant_cost_citizen i 
                    WHERE i.mid = j.mid  AND i.event = 1 and i.citizen  IS NOT NULL ) AS rcount 
                    FROM restaurant_cost_citizen j GROUP BY j.mid) rx   
                    ON rx.mid = rm.id';
        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['event' => $event]);

        return $execute->fetchAll();
    }

    //

    /*
    public function findOneBySomeField($value): ?RestaurantMeal
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
