<?php

namespace App\Repository;

use App\Entity\RestaurantReal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RestaurantReal|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurantReal|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurantReal[]    findAll()
 * @method RestaurantReal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantRealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RestaurantReal::class);
    }

    /**
     * @return RestaurantReal[] Returns an array of RestaurantReal objects
    */

    public function findByEvent($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.event = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
    * @return Name and Surname of RestaurantReal objects
    */   
    public function findNameByRestaurant($restaurant) {
        
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT r.name, r.surname FROM restaurant_real r WHERE r.id = :restaurant';
        
        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['restaurant' => $restaurant]);

        return $execute->fetchAll();
    }
    
    /**
     * @return RestaurantReal[] Returns an array of RestaurantReal objects
     */
    public function findAllocationMap($event)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT c.id cid, c.task, c.name, c.surname, c.birth_date, rcc.id rccid, rcc.name rccname, rcc.book_date, rrm.id rrmid, rrm.name rrmname, rcc.price 
        selling_price, rrmp.price supplier_cost, rr.id rrid, rr.name rrname, rr.surname rrsurname FROM (SELECT * FROM restaurant_match rm where d = 0) rm 
        LEFT JOIN restaurant_cost_citizen rcc ON rcc.id = rm.restaurantcost
        LEFT JOIN restaurant_real_meal rrm ON rm.mealreal = rrm.id
        LEFT JOIN restaurant_real_meal_price rrmp ON rrmp.restaurant_real_meal = rrm.id
        LEFT JOIN (SELECT c.* FROM citizen c WHERE c.eid = :event AND c.deleted = 0) c ON rcc.citizen = c.id
        LEFT JOIN (SELECT * FROM restaurant_real rr WHERE rr.event = :event) rr ON rr.id = rrm.restaurant_real
        WHERE c.id IS NOT NULL';
        
        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['event' => $event]);

        return $execute->fetchAll();
    }
    
}
