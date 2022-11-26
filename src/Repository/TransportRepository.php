<?php

namespace App\Repository;

use App\Entity\Transport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Transport|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transport|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transport[]    findAll()
 * @method Transport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transport::class);
    }
    
    /**
     * @return Transport[] Returns an array of Transport objects
     */
    public function findAllocationMap($event)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT c.id cid, c.task, c.name, c.surname, c.birth_date, bcc.price price, b.name bname, 
                b.id bid, t.name tname, t.id tid 
                FROM bus_cost_citizen bcc 
                LEFT JOIN bus_cost bc ON bcc.bus_cost = bc.id
                LEFT JOIN bus b ON b.id = bc.bus
                LEFT JOIN (SELECT c.* FROM citizen c WHERE c.eid = :event AND c.deleted = 0) c ON bcc.citizen = c.id
                LEFT JOIN (SELECT * FROM transport t WHERE t.event = :event) t on b.transport = t.id
                WHERE c.id IS NOT NULL';
        
        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['event' => $event]);

        return $execute->fetchAll();
    }
    

//    /**
//     * @return Transport[] Returns an array of Transport objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Transport
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
