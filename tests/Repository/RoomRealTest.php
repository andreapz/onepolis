<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Test\Repository;

use App\Entity\RestaurantMeal;
use App\Entity\RoomReal;
use App\Entity\RoomBase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RoomRealTest extends KernelTestCase {
       
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testFindRoomReal()
    {
        $repository = $this->entityManager
            ->getRepository(RoomReal::class)
        ;
        
        $rr = $repository->find(1);
        
        $this->assertSame(1, $rr->getHotel()->getId());
        $hotelReal = $rr->getHotel();
        
        $this->assertSame(9, count($hotelReal->getRooms()));
        
        $hotel = $hotelReal->getHotel();
        $this->assertSame(2, count($hotel->getRooms()));
        
        
        
    }
   

    protected function tearDown()
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
    
}
