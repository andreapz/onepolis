<?php

namespace App\Repository;

use App\Entity\Address;
//use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Address|null find($id, $lockMode = null, $lockVersion = null)
 * @method Address|null findOneBy(array $criteria, array $orderBy = null)
 * @method Address[]    findAll()
 * @method Address[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class);
    }



    /**
    * @return Address[] Returns an array of Address objects
    */
    public function findCitiesReport($event) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'select count(*), a.city from 
                (select a.* from (select * from citizen c where c.eid = :event and c.deleted = 0) c 
                left join address a on c.address_id = a.id) a group by city';
        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['event' => $event]);

        return $execute->fetchAll();
    }

    /**
    * @return Address[] Returns an array of Address objects
    */
    public function findProvinceReport($event) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'select count(*), a.province from 
                (select a.* from (select * from citizen c where c.eid = :event and c.deleted = 0) c 
                left join address a on c.address_id = a.id) a group by province';
        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['event' => $event]);

        return $execute->fetchAll();
    }

    /**
    * @return Address[] Returns an array of Address objects
    */
    public function findStateReport($event) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'select count(*), a.state from 
                (select a.* from (select * from citizen c where c.eid = :event and c.deleted = 0) c 
                left join address a on c.address_id = a.id) a group by state';
        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['event' => $event]);

        return $execute->fetchAll();
    }

    
    


//    /**
//     * @return Address[] Returns an array of Address objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Address
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
