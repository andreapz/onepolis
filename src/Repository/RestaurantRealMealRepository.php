<?php

namespace App\Repository;

use App\Entity\RestaurantRealMeal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RestaurantRealMeal|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurantRealMeal|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurantRealMeal[]    findAll()
 * @method RestaurantRealMeal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantRealMealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RestaurantRealMeal::class);
    }
    
    
    /**
    * @return RestaurantRealMeal[] Returns an array of RestaurantRealMeal objects
    */
    public function findFreeByRestaurant($restaurant) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT r.*, rr.name rname, rr.surname rsurname FROM
	(SELECT r.*, rm.* FROM 
		(SELECT * FROM `restaurant_real_meal` WHERE rid = :restaurant) r  
		LEFT JOIN (SELECT j.mealreal, (SELECT COUNT(*) FROM restaurant_match i WHERE i.mealreal = j.mealreal  AND i.restaurant = :restaurant AND i.d = 0) AS rcount FROM restaurant_match j GROUP BY j.mealreal) rm 
		ON r.id = rm.mealreal) r
	LEFT JOIN restaurant_real rr ON r.restaurant_real = rr.id';

        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['restaurant' => $restaurant]);

        return $execute->fetchAll();
    }

//    /**
//     * @return RestaurantRealMeal[] Returns an array of RestaurantRealMeal objects
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
    public function findOneBySomeField($value): ?RestaurantRealMeal
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
