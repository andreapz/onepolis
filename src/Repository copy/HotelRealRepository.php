<?php

namespace App\Repository;

use App\Entity\HotelReal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HotelReal|null find($id, $lockMode = null, $lockVersion = null)
 * @method HotelReal|null findOneBy(array $criteria, array $orderBy = null)
 * @method HotelReal[]    findAll()
 * @method HotelReal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HotelRealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HotelReal::class);
    }

    /**
     * @return HotelReal[] Returns an array of HotelReal objects
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
    * @return Name and Surname of HotelReal objects
    */   
    public function findNameByHotel($hotel) {
        
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT h.name, h.surname FROM hotel_real h WHERE h.id = :hotel';
        
        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['hotel' => $hotel]);

        return $execute->fetchAll();
    }
    
    /**
    * @return Name and Surname of HotelReal objects
    */   
    public function findAllocationMap($event) {
        
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'select * from (select hm.id, hm.roomreal, hm.note, rr.id rrid, hr.id hrid, hr.name hrname,
            hr.surname hrsurname, rr.name rrname, rr.guests, 
            c.id cid, c.name, c.surname, c.birth_date cbirthdate, c.task, 
            c.city_birth city_birth, c.note cnote, c.room_note roomnote, 
            a.street street, a.city city, a.postcode postcode, a.province province, a.state state,
            rcc.name rccname, r.id rid, r.name rname, bcc.transport, bcc.name busname, restrm.id restid, restrm.name mealname, restmatch.d restmatchd from 
				(select * from hotel_match hm where hm.d = 0) hm 
                left join room_real rr on rr.id = hm.roomreal
                left join (select * from hotel_real hr where event = :event) hr on rr.hotel_real = hr.id
                left join (select * from citizen c where c.deleted = 0) c on hm.citizen = c.id
                LEFT JOIN address a on a.id = c.address_id
                left join restaurant_cost_citizen restcc on restcc.citizen = c.id
                left join (SELECT * FROM restaurant_match restmatch WHERE restmatch.d = 0) restmatch on restmatch.restaurantcost = restcc.id
                left join restaurant_real_meal restrm on restrm.id = restmatch.mealreal 
				left join room_cost_citizen rcc on rcc.citizen = c.id
				left join (select * from bus_cost_citizen bcc where bcc.event = :event) bcc on bcc.citizen = c.id
                left join room r on rcc.rid = r.id) x
                where x.id is not null AND x.cid IS NOT NULL';
        
        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['event' => $event]);

        return $execute->fetchAll();
    }
    
    /**
    * @return Name and Surname of HotelReal objects
    */   
    public function findPrices($event) {
        
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT hr.id hrid, rr.id rrid, hr.name, hr.surname, rr.name rrname, rr.guests, rrp.price, 
                rrp.guests person, rrp.id rrpid FROM
                (SELECT * FROM hotel_real hr WHERE hr.event = :event) hr 
                LEFT JOIN room_real rr ON hr.id = rr.hotel_real 
                LEFT JOIN room_real_price rrp ON rr.id = rrp.room_real';
        
        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['event' => $event]);

        return $execute->fetchAll();
    }
}
