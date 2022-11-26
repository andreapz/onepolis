<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    /**
     * @return Room Returns an instance of Room object
     */
    
    public function findByRoomId($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    

    /**
     * @return Room Returns an instance of Room object
     */
    public function findCountByEvent($event)
    {
        $conn = $this->getEntityManager()->getConnection();
// removed check on citizen deleted
        /*$sql = 'SELECT rm.id, rm.total, rx.rcount FROM (SELECT * FROM hotel h WHERE event = :event) h
	LEFT JOIN room rm ON h.id = rm.hid 
	LEFT JOIN (SELECT j.rid rid, (SELECT COUNT(*) FROM (SELECT rcc.* FROM (SELECT * FROM room_cost_citizen rcc WHERE rcc.citizen IS NOT NULL) rcc
        LEFT JOIN (select * from citizen c where c.deleted = 0) c 
        ON rcc.citizen = c.id WHERE c.id IS NOT NULL) i WHERE i.rid = j.rid  AND i.event = :event) AS rcount FROM (SELECT rcc.* FROM (SELECT * FROM room_cost_citizen rcc WHERE rcc.citizen IS NOT NULL) rcc
        LEFT JOIN (select * from citizen c where c.deleted = 0) c 
        ON rcc.citizen = c.id WHERE c.id IS NOT NULL) j GROUP BY j.rid) rx   
	ON rx.rid = rm.id';*/
        $sql = 'SELECT rm.id, rm.total, rx.rcount FROM (SELECT * FROM room rm WHERE eid = :event) rm
                LEFT JOIN (SELECT j.rid rid, (SELECT COUNT(*) 
                FROM (SELECT rcc.* FROM (SELECT * FROM room_cost_citizen rcc WHERE rcc.citizen IS NOT NULL) rcc) i 
                WHERE i.rid = j.rid  AND i.event = 1) AS rcount 
                FROM (SELECT rcc.* 
                FROM (SELECT * FROM room_cost_citizen rcc WHERE rcc.citizen IS NOT NULL) rcc) j GROUP BY j.rid) rx   
            ON rx.rid = rm.id';
        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['event' => $event]);

        return $execute->fetchAll();
    }
    
    /*
    public function findOneBySomeField($value): ?Room
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
