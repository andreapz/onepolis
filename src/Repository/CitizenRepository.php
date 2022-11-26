<?php

namespace App\Repository;

use App\Entity\Citizen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Citizen|null find($id, $lockMode = null, $lockVersion = null)
 * @method Citizen|null findOneBy(array $criteria, array $orderBy = null)
 * @method Citizen[]    findAll()
 * @method Citizen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CitizenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Citizen::class);
    }


    /**
    * @return Citizen[] Returns an array of Citizen objects
    */
    public function findById($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id = :query')
            ->setParameter('query', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Citizen[] Returns an array of Citizen objects
    */
    public function findByName($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('(c.name like :query OR c.surname like :query)')
            ->andWhere('c.deleted = 0')
            ->setParameter('query', "%". $value ."%")
            ->orderBy('c.surname', 'ASC')
            // ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    
    /* *
    *  @return Citizen[] Returns an array of Citizen objects
    
    public function findByUser($event, $value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.eid = :event')
            ->andWhere('c.uid = :query')
            ->setParameter('event', $event)
            ->setParameter('query', $value)
            // ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }*/
    
    /**
    * @return Citizen[] Returns an array of Citizen objects 
    */
    public function findByUser($event, $user, $room) {

        $search = 'AND c.uid = ' . $user;
        
        return $this->findByAdmin($event, $search, $room);
    }

    /**
    * @return Citizen[] Returns an array of Citizen objects
    */
    public function findByAdmin($event, $search, $room) {
        $preroom = '';
        $postroom = '';
        if ($room > 0) {
            $preroom = '(SELECT * FROM (SELECT c.* FROM (SELECT * FROM room_cost_citizen rcc WHERE rcc.rid = ' . $room .' ) rcc
                        LEFT JOIN';
            $postroom = 'ON rcc.citizen = c.id) c WHERE c.id IS NOT NULL ) c ';
        }
        
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT c.id cid, c.task, c.name, surname, birth_date, city_birth, phone, c.email, gender, need_support, 
                c.note, branch_id, relationship_id, delegate, guest, c.uid, room_note, street, city, postcode, province, 
                state, f.username, b.name branchname, r.name relationshipname, t.amount, t.ordered, t.ordered_date, t.code, 
                room.id roomid, roombase.hotel hotel, room.name roomname, rcc.price roomprice, restcc.id restccid, 
                restcc.book_date mealdate, restcc.price mealprice, rm.name mealname, rm.restaurant restaurant, 
                rm.id mealid, bcc.price busprice, bcc.name busname 
                FROM ' . $preroom .' (SELECT * FROM citizen c WHERE c.eid = :event AND c.deleted = 0 ' . $search .' ) c ' . $postroom .' 
                LEFT JOIN address a ON c.address_id = a.id LEFT JOIN fos_user f on c.uid = f.id 
                LEFT JOIN branch b ON c.branch_id = b.id 
                LEFT JOIN relationship r ON c.relationship_id = r.id 
                LEFT JOIN task t ON c.task = t.id 
                LEFT JOIN room_cost_citizen rcc ON c.id = rcc.citizen 
                LEFT JOIN room room ON rcc.rid = room.id
                LEFT JOIN room_base roombase ON roombase.id = room.room_base 
                LEFT JOIN restaurant_cost_citizen restcc ON c.id = restcc.citizen 
                LEFT JOIN restaurant_meal rm ON restcc.mid = rm.id 
                LEFT JOIN bus_cost_citizen bcc on bcc.citizen = c.id';
        
        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['event' => $event]);

        return $execute->fetchAll();
    }
    
    /**
    * @return Citizen[] Returns an array of Citizen objects
    */
    public function findByUser2($event, $user) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT c.id cid, c.task, c.name, surname, birth_date, city_birth, phone, c.email, gender, need_support, c.note, branch_id, relationship_id, delegate, guest, c.uid, room_note, street, city, postcode, province, state, payed, ordered, ordered_date, amount, t.code, cp.id cpid, cp.value, description, type, payment_date, update_date, f.username, b.name branchname, r.name relationshipname '
                . 'FROM (SELECT * FROM citizen c WHERE c.uid = :user AND c.eid = :event AND c.deleted = 0) c LEFT JOIN address a ON c.address_id = a.id LEFT JOIN task t on c.task = t.id LEFT JOIN (SELECT * FROM citizen_payment cp WHERE cp.deleted = 0) cp ON t.code = cp.code LEFT JOIN fos_user f on c.uid = f.id LEFT JOIN branch b ON c.branch_id = b.id LEFT JOIN relationship r ON c.relationship_id = r.id';
        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['user' => $user, 'event' => $event]);

        return $execute->fetchAll();
    }

    /**
    * @return Citizen[] Returns an array of Citizen objects
    */
    public function findByNameAndUser($value, $userid)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('(c.name like :query OR c.surname like :query)')
            ->andWhere('c.uid = :userid')
            ->andWhere('c.deleted = 0')
            ->setParameter('query', "%". $value ."%")
            ->setParameter('query', "%". $value ."%")
            ->setParameter('userid', $userid)
            ->orderBy('c.surname', 'ASC')
            // ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        /*
        
        conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM citizen c WHERE (c.name like :query OR c.surname like :query) AND c.uid = :userid';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['query' => "%". $value ."%", 'userid' => $userid]);

        return $stmt->fetchAll();
        */
    }

    /**
    * @return Citizen[] Returns an array of Citizen objects
    */
    public function findByHotel($event, $hotel) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT cx.* from (SELECT * FROM room_base rb WHERE rb.hotel = :hotel) rb 
            LEFT JOIN room_cost_citizen rcc ON rcc.rbid = rb.id 
            INNER JOIN 
                (SELECT * from citizen c WHERE c.eid = :event AND c.deleted = 0 AND c.id NOT IN 
                (SELECT hm.citizen FROM hotel_match hm  WHERE d = 0) ORDER BY c.need_support DESC) cx ON cx.id = rcc.citizen';

                
        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['hotel' => $hotel, 'event' => $event]);

        return $execute->fetchAll();
    }

    /**
    * @return Citizen[] Returns an array of Citizen objects
    */
    public function findByMatch($hotel) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT c.*, hmmid, hmmname, hmmrooms, hmmhmid, hmmfloor, hmmguests, hmmbath, hmmaccess, hmmsingle, hmmdouble, hmmtwin, hmmsofa, hmmbunk, hrid, hrname, hrsurname 
                FROM (SELECT hmm.citizen, hmm.id hmmid, hmm.hmid hmmhmid, hmm.name hmmname, hmm.rooms hmmrooms, hmm.floor hmmfloor, hmm.guests hmmguests, 
                    hmm.bath hmmbath, hmm.access hmmaccess, hmm.single hmmsingle, hmm.doublebed hmmdouble, hmm.twin hmmtwin, hmm.sofa hmmsofa, 
                    hmm.bunk hmmbunk, hr.id hrid, hr.name hrname, hr.surname hrsurname 
                    FROM (SELECT hm.citizen, hm.id hmid, rr.id, rr.name, rr.rooms, rr.floor, rr.guests, rr.bath, rr.access, rr.single , rr.doublebed, 
                        rr.twin, rr.sofa, rr.bunk, rr.hotel_real 
                        FROM (SELECT hm.citizen, hm.roomreal, hm.id
                            FROM `hotel_match` hm WHERE d = 0) hm 
                            LEFT JOIN room_real rr ON hm.roomreal = rr.id) hmm 
                            LEFT JOIN hotel_real hr ON hmm.hotel_real = hr.id 
                            WHERE hr.hotel = :hotel) hmm 
                            LEFT JOIN citizen c ON hmm.citizen = c.id WHERE c.deleted = 0';

        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['hotel' => $hotel]);

        return $execute->fetchAll();
    }
    
    /**
    * @return Citizen[] Returns an array of Citizen objects
    */
    public function findTicketDuplicated($event) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'Select c.id cid, tcc.id tid FROM (SELECT * FROM citizen c WHERE c.eid = :event) c
                LEFT JOIN ticket_cost_citizen tcc ON c.id = tcc.citizen 
                WHERE tcc.id IS NOT NULL ORDER BY tcc.id DESC';

        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['event' => $event]);

        return $execute->fetchAll();
    }


    /**
    * @return Citizen[] Returns an array of Citizen objects
    */
    public function findByRestaurant($restaurant) {

        $conn = $this->getEntityManager()->getConnection();
        
        $sql = 'SELECT c.* , rcc.id mid, rcc.name mname, rcc.type mtype, rmeal.name mealname, rmeal.meal_date mealdate FROM
        (SELECT * from restaurant_cost_citizen rcc WHERE rcc.restaurant = :restaurant AND rcc.citizen IS NOT NULL AND rcc.id NOT IN 
            (SELECT rm.restaurantcost FROM restaurant_match rm  WHERE d = 0) ) rcc
        LEFT JOIN citizen c ON c.id = rcc.citizen
        LEFT JOIN restaurant_meal rmeal ON rcc.mid = rmeal.id
';

        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['restaurant' => $restaurant]);

        return $execute->fetchAll();
    }

    /**
    * @return Citizen[] Returns an array of Citizen objects
    */
    public function findByMeal($meal) {

        $conn = $this->getEntityManager()->getConnection();

        
        $sql = 'SELECT c.* , rcc.id mid, rcc.name mname, rcc.type mtype, rmeal.name mealname, rmeal.meal_date mealdate FROM
        (SELECT * from restaurant_cost_citizen rcc WHERE rcc.mid = :meal AND rcc.citizen IS NOT NULL AND rcc.id NOT IN 
            (SELECT rm.restaurantcost FROM restaurant_match rm  WHERE d = 0) ) rcc
        LEFT JOIN citizen c ON c.id = rcc.citizen
        LEFT JOIN restaurant_meal rmeal ON rcc.mid = rmeal.id
';

        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['meal' => $meal]);

        return $execute->fetchAll();
    }
    
    
    /**
    * @return Citizen[] Returns an array of Citizen objects
    */
    public function findByRestaurantMatch($restaurant) {

        $conn = $this->getEntityManager()->getConnection();

        
        $sql = '
            SELECT citizenid, cname, csurname, ctask, rcctype, rccid, rccname, rccdate, rmid, mealreal, rrm.name rrmname, rmrestaurant, rr.name rrname, rr.surname rrsurname, rcctype 
	FROM (SELECT c.id citizenid, c.name cname, c.surname csurname, c.task ctask, rcc.id rccid, rcc.name rccname, rcc.book_date rccdate, rm.id rmid, rm.mealreal mealreal, rm.restaurant rmrestaurant, rcc.type rcctype 
	FROM restaurant_cost_citizen rcc 
	LEFT JOIN restaurant_match rm ON rcc.id = rm.restaurantcost 
	LEFT JOIN (SELECT * FROM citizen c WHERE c.deleted = 0) c ON c.id = rcc.citizen 
            WHERE rm.restaurant = :restaurant AND rm.d = 0) rm 
	LEFT JOIN restaurant_real_meal rrm on rm.mealreal = rrm.id 
	LEFT JOIN restaurant_real rr on rrm.restaurant_real = rr.id
        WHERE citizenid IS NOT NULL
';

        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['restaurant' => $restaurant]);

        return $execute->fetchAll();
    }
    
    
    /**
    * @return Citizen[] Returns an array of Citizen objects
    */
    public function findRestaurantMatchedByCitizen($citizen) {

        $conn = $this->getEntityManager()->getConnection();

        
        $sql = 'SELECT rx.citizen, rx.restaurantcost, rrm.name, rr.name, rr.surname FROM 
            (SELECT rcc.citizen, rm.* FROM 
            (SELECT * FROM restaurant_cost_citizen rcc WHERE rcc.citizen = :citizen) rcc 
            LEFT JOIN restaurant_match rm on rcc.id = rm.restaurantcost WHERE rm.d = 0) rx 
            LEFT JOIN restaurant_real_meal rrm on rx.mealreal = rrm.id 
            LEFT JOIN restaurant_real rr on rx.restaurant = rr.id
';

        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['citizen' => $citizen]);

        return $execute->fetchAll();
    }
    
    
    //SELECT c.* from (SELECT * FROM room r WHERE r.hotel = :hotel) r LEFT JOIN room_cost_citizen rcc ON rcc.rid = r.id INNER JOIN (SELECT * from citizen c WHERE c.eid = 1 AND c.id NOT IN (SELECT hm.citizen FROM hotel_match hm) ORDER BY c.need_support DESC) c ON c.id = rcc.citizen

//    /**
//     * @return Citizen[] Returns an array of Citizen objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Citizen
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
