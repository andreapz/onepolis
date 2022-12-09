<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Test\Repository;

use App\Entity\RestaurantMeal;
use App\Entity\Room;
use App\Entity\RoomMeal;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RepositoryTest extends KernelTestCase {
       
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testFindMealCountByEvent()
    {
        $repository = $this->entityManager
            ->getRepository(RestaurantMeal::class)
        ;
        
        //var_dump($product);
        //$this->assertSame(14.50, $product->getPrice());
        
        $jmealCount = RestaurantMeal::checkMealsCount($repository, 1);
        $mealCount = json_decode($jmealCount);
        //var_dump($mealCount->{'1'}->{'c'});
        
        $this->assertNotNull($mealCount);
        $this->assertEquals("1",$mealCount->{'1'}->{'c'});
        
    }
    
    public function testFindRoomCountByEvent()
    {
        $repository = $this->entityManager
            ->getRepository(Room::class)
        ;
        
        $jroomCount = Room::checkRoomsCount($repository, 1);
        $roomCount = json_decode($jroomCount);
        //var_dump($roomCount->{'1'}->{'c'});
        
        $this->assertNotNull($roomCount);
        $this->assertEquals("1",$roomCount->{'1'}->{'c'});
        
    }
    
    public function testFindRoomMealsByEvent()
    {
        $repository = $this->entityManager
            ->getRepository(RoomMeal::class)
        ;
        
        $jroomMealCount = RoomMeal::checkRoomMeals($repository, 1);
        $roomMealCount = json_decode($jroomMealCount);
        
        $this->assertNotNull($roomMealCount);
        $this->assertEquals("s",$roomMealCount->{'3'}->{'1'});
        
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
    
}
