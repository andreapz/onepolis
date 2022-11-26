<?php

namespace App\Repository;

use App\Entity\RoomReal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RoomReal|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomReal|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomReal[]    findAll()
 * @method RoomReal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomReal::class);
    }

    /**
    * @return RoomReal[] Returns an array of RoomReal objects
    */
    public function findFreeByHotel($hotel) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT rx.* FROM  (SELECT rr.* FROM room_real rr RIGHT JOIN (SELECT * FROM hotel_real hr WHERE hr.hotel = :hotel) hr ON hr.id = rr.hotel_real WHERE rr.hotel_real IS NOT NULL) rx WHERE rx.id NOT IN (SELECT hm.roomreal FROM hotel_match hm WHERE hm.d = 0 GROUP BY  hm.roomreal)';

        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['hotel' => $hotel]);

        return $execute->fetchAll();
    }
    
    /**
    * @return RoomReal[] Returns an array of RoomReal objects
    */
    public function findHotelByCitizen($citizen) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT hm.citizen, rr.name room, hr.name, hr.surname FROM (SELECT * FROM hotel_match hm WHERE hm.citizen = :citizen AND hm.d = 0) hm LEFT JOIN room_real rr on hm.roomreal = rr.id LEFT JOIN hotel_real hr on rr.hotel_real = hr.id';

        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['citizen' => $citizen]);

        return $execute->fetchAll();
    }
    
    
    

    /**
     * @return RoomReal[] Returns an array of RoomReal objects
     */

    public function findByHotel($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.hotel_real = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    /**
     * @return RoomReal Returns a RoomReal object or null if not exist
     */

    public function findByCitizen($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.citizen = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @return RoomReal Returns a RoomReal object or null if not exist
     */

    public function findById($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id = :val')
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
