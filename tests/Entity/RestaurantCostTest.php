<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Test\Entity;

use App\Entity\Restaurant;
use App\Entity\RestaurantCost;
use App\Entity\Citizen;
use App\Entity\RestaurantMeal;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class RestaurantCostTest extends TestCase {

    public static function createRestaurant($id, $instance) {
        $restaurantManager = $instance->createMock(Restaurant::class);
        $restaurantManager->expects($instance->any())
                ->method('getId')->willReturn($id);
        return $restaurantManager;
    }
    
    public static function createTicket($id, $price, $name,
            $minAge, $maxAge, $initialDate, $endDate, $type, $mealManager,
            $instance) {
        $ticketManager = $instance->createMock(RestaurantCost::class);
        $ticketManager->expects($instance->any())
                ->method('getId')->willReturn($id);
        $ticketManager->expects($instance->any())
                ->method('getPrice')->willReturn($price);
        $ticketManager->expects($instance->any())
                ->method('getName')->willReturn($name);
        $ticketManager->expects($instance->any())
                ->method('getMinAge')->willReturn($minAge);
        $ticketManager->expects($instance->any())
                ->method('getMaxAge')->willReturn($maxAge);
        $ticketManager->expects($instance->any())
                ->method('getBookInitDate')->willReturn($initialDate);
        $ticketManager->expects($instance->any())
                ->method('getBookEndDate')->willReturn($endDate);
        $ticketManager->expects($instance->any())
                ->method('getType')->willReturn($type);
        $ticketManager->expects($instance->any())
                ->method('getMeal')->willReturn($mealManager);

        return $ticketManager;
    }

    public static function createTicket1($mealManager, $instance) {
        return RestaurantCostTest::createTicket(1, "11,00", "TK1", 10, 100,
                        new \DateTime("2020-01-01"),
                        new \DateTime("2020-03-01"),
                        "pranzo",
                        $mealManager, $instance);
    }

    public static function createTicket2($mealManager, $instance) {
        return RestaurantCostTest::createTicket(2, "7,00", "TK1b", 0, 9,
                        new \DateTime("2020-01-01"),
                        new \DateTime("2020-05-01"),
                        "pranzo",
                        $mealManager, $instance);
    }

    public static function createTicket3($mealManager, $instance) {
        return RestaurantCostTest::createTicket(3, "22,00", "TK2", 10, 100,
                        new \DateTime("2020-03-02"),
                        new \DateTime("2020-05-01"),
                        "pranzo",
                        $mealManager, $instance);
    }

    public static function createTicket4($mealManager, $instance) {
        return RestaurantCostTest::createTicket(1, "13,00", "TC1", 0, 100,
                        new \DateTime("2020-01-01"),
                        new \DateTime("2020-05-01"),
                        "pranzo",
                        $mealManager, $instance);
    }

    public static function createMeal1($mealId, $instance) {
        $mealManager = $instance->createMock(RestaurantMeal::class);
        $mealManager->expects($instance->any())
                ->method('getId')->willReturn($mealId);
        $mealManager->expects($instance->any())
                ->method('getMealDate')->willReturn(new \DateTime("2020-04-25"));
        
        $tickets = new ArrayCollection();
        $tickets[] = $instance->createTicket1($mealManager, $instance);
        $tickets[] = $instance->createTicket2($mealManager, $instance);
        $tickets[] = $instance->createTicket3($mealManager, $instance);
       
        $mealManager->expects($instance->any())
                ->method('getTickets')->willReturn($tickets);

        $mealManager->expects($instance->any())
                ->method('getRestaurant')->willReturn(
                        RestaurantCostTest::createRestaurant(1, $instance));
        
        return $mealManager;
    }
    
    public static function createMeal2($mealId, $instance) {
        $mealManager = $instance->createMock(RestaurantMeal::class);
        $mealManager->expects($instance->any())
                ->method('getId')->willReturn($mealId);
        $mealManager->expects($instance->any())
                ->method('getMealDate')->willReturn(new \DateTime("2020-04-25"));

        $tickets = new ArrayCollection();
        $tickets[] = RestaurantCostTest::createTicket4($mealManager, $instance);
        
        $mealManager->expects($instance->any())
                ->method('getTickets')->willReturn($tickets);
        
        $mealManager->expects($instance->any())
                ->method('getRestaurant')->willReturn(
                        RestaurantCostTest::createRestaurant(2, $instance));
        
        return $mealManager;
    }

    public function testTicketIsValid() {
        $mealId = 3;
        $mealId2 = 4;
        $mealManager = RestaurantCostTest::createMeal1($mealId, $this);
        $mealManager2 = RestaurantCostTest::createMeal1($mealId2, $this);
        $ticket1Manager = RestaurantCostTest::createTicket1($mealManager, $this);
        $ticket2Manager = RestaurantCostTest::createTicket2($mealManager, $this);
        $ticket4Manager = RestaurantCostTest::createTicket4($mealManager2, $this);

        $citizen = new Citizen();
        $citizen->setBirthDate(new \DateTime("1978-08-21"));

        $now1 = new \DateTime("2020-02-01");

        $this->assertEquals(true, RestaurantCost::isTicketValid($ticket1Manager,
                        $mealId, $now1, $citizen->getBirthDate()));

        $this->assertEquals(false, RestaurantCost::isTicketValid($ticket2Manager,
                        $mealId, $now1, $citizen->getBirthDate()));

        $this->assertEquals(true, RestaurantCost::isTicketValid($ticket4Manager,
                        $mealId2, $now1, $citizen->getBirthDate()));

        
        $now2 = new \DateTime("2020-03-02");

        $this->assertEquals(false, RestaurantCost::isTicketValid($ticket1Manager,
                        $mealId, $now2, $citizen->getBirthDate()));

        $this->assertEquals(false, RestaurantCost::isTicketValid($ticket2Manager,
                        $mealId, $now2, $citizen->getBirthDate()));

        
        $citizen->setBirthDate(new \DateTime("2019-08-21"));

        $this->assertEquals(false, RestaurantCost::isTicketValid($ticket1Manager,
                        $mealId, $now2, $citizen->getBirthDate()));

        $this->assertEquals(true, RestaurantCost::isTicketValid($ticket2Manager,
                        $mealId, $now2, $citizen->getBirthDate()));

    }

    public function testCreateNewTicket() {
        $eventId = 1;
        $mealId1 = 3;
        
        $restaurantId = 1;
        $meal1Manager = RestaurantCostTest::createMeal1($mealId1, $this);
        
        $userId = 1;
        $cid = 5;

        $citizen = new Citizen();
        $citizen->setBirthDate(new \DateTime("1978-08-21"));

        $now1 = new \DateTime("2020-02-01");
        $resp1 = Citizen::createNewTicketsMeal($eventId, $restaurantId,
                        $citizen, $meal1Manager, $userId, $cid,
                        $now1);

        $this->assertNotNull($resp1);
        $this->assertEquals("TK1", $resp1->getName());
        $this->assertNotEquals("TK2", $resp1->getName());

        $citizen->setBirthDate(new \DateTime("2019-08-21"));
        $resp2 = Citizen::createNewTicketsMeal($eventId, $restaurantId,
                        $citizen, $meal1Manager, $userId, $cid,
                        $now1);

        $this->assertNotNull($resp2);
        $this->assertEquals("TK1b", $resp2->getName());
        $this->assertNotEquals("TK2", $resp2->getName());
    }

    public function testCreateUpdateAdding2Ticket() {
        $eventId = 1;
        $mealId1 = 3;
        $mealId2 = 4;
        $restaurantId = 1;
        $meal1Manager = RestaurantCostTest::createMeal1($mealId1, $this);
        $meal2Manager = RestaurantCostTest::createMeal2($mealId2, $this);

        $meals = array();
        $meals[] = $meal1Manager;
        $meals[] = $meal2Manager;

        $userId = 1;
        $cid = 5;

        $citizen = new Citizen();
        $citizen->setBirthDate(new \DateTime("1978-08-21"));

        $now1 = new \DateTime("2020-02-01");

        $tickets = array();

        $resp1 = Citizen::createNewTicketsMeal($eventId, $restaurantId,
                        $citizen, $meal1Manager, $userId, $cid,
                        $now1);
        $tickets[$meal1Manager->getId()] = $resp1;

        $this->assertNotNull($resp1);
        $this->assertEquals("TK1", $resp1->getName());
        $this->assertNotEquals("TK2", $resp1->getName());


        $resp2 = Citizen::createNewTicketsMeal($eventId, $restaurantId,
                        $citizen, $meal2Manager, $userId, $cid,
                        $now1);

        $tickets[$meal2Manager->getId()] = $resp2;
        
        $this->assertNotNull($resp2);
        $this->assertEquals("TC1", $resp2->getName());
        $this->assertNotEquals("TK2", $resp2->getName());
        
        $citizen->updateTicketsMeal($tickets);
        
        $ticketrestaurant = $citizen->getTicketsrestaurant();
        $this->assertEquals(2, count($ticketrestaurant));
        
        //----------------------
        $citizen->setBirthDate(new \DateTime("2019-08-21"));
        $tickets2 = array();
        
        $resp3 = Citizen::createNewTicketsMeal($eventId, $restaurantId,
                        $citizen, $meal1Manager, $userId, $cid,
                        $now1);
        $tickets2[$meal1Manager->getId()] = $resp3;

        $this->assertNotNull($resp3);
        $this->assertEquals("TK1b", $resp3->getName());
        $this->assertNotEquals("TK2", $resp3->getName());


        $resp4 = Citizen::createNewTicketsMeal($eventId, $restaurantId,
                        $citizen, $meal2Manager, $userId, $cid,
                        $now1);

        $tickets2[$meal2Manager->getId()] = $resp4;
        
        $this->assertNotNull($resp4);
        $this->assertEquals("TC1", $resp4->getName());
        $this->assertNotEquals("TK2", $resp4->getName());
        
        $citizen->updateTicketsMeal($tickets2);
        
        $ticketrestaurant2 = $citizen->getTicketsrestaurant();
        $this->assertEquals(2, count($ticketrestaurant2));
        //var_dump($ticketrestaurant2);
        //$ticket3 = $ticketrestaurant2->firts();
        
        
        $this->assertEquals(false, $ticketrestaurant2->contains($resp1));
        $this->assertEquals(true, $ticketrestaurant2->contains($resp2));
        $this->assertEquals(true, $ticketrestaurant2->contains($resp3));
        $this->assertEquals(false, $ticketrestaurant2->contains($resp4));
        //$this->assertEquals("TK1b", $resp3->getName());
        
     
        //----------------------
        $citizen->setBirthDate(new \DateTime("2019-08-21"));
        $tickets3 = array();
        
        $resp5 = Citizen::createNewTicketsMeal($eventId, $restaurantId,
                        $citizen, $meal1Manager, $userId, $cid,
                        $now1);
        $tickets3[$meal1Manager->getId()] = $resp5;

        $this->assertNotNull($resp5);
        $this->assertEquals("TK1b", $resp5->getName());
        $this->assertNotEquals("TK2", $resp5->getName());
        
        $citizen->updateTicketsMeal($tickets3);
        
        $ticketrestaurant3 = $citizen->getTicketsrestaurant();
        $this->assertEquals(1, count($ticketrestaurant3));
        
        
        $this->assertEquals(false, $ticketrestaurant3->contains($resp1));
        $this->assertEquals(false, $ticketrestaurant3->contains($resp2));
        $this->assertEquals(true, $ticketrestaurant3->contains($resp3));
        $this->assertEquals(false, $ticketrestaurant3->contains($resp4));
        $this->assertEquals(false, $ticketrestaurant3->contains($resp5));
        
    }

    public function testRegisterTicket() {
        $eventId = 1;
        $mealId1 = 3;
        $mealId2 = 4;
        
        $meal1Manager = RestaurantCostTest::createMeal1($mealId1, $this);
        $meal2Manager = RestaurantCostTest::createMeal2($mealId2, $this);

        $meals = array();
        $meals[] = $meal1Manager;
        $meals[] = $meal2Manager;

        $userId = 1;
        $cid = 5;

        $citizen = new Citizen();
        $citizen->setBirthDate(new \DateTime("1978-08-21"));

        $now1 = new \DateTime("2020-02-01");

        $citizen->registerMeals([$meal1Manager], $meals, $eventId, 
                $userId, $cid, $now1);
        $tickets1 = $citizen->getTicketsrestaurant();
        $this->assertEquals(1, count($tickets1));
        $ticket1_1 = $tickets1[0];
        $this->assertEquals("TK1", $ticket1_1->getName());
        
        $citizen->registerMeals([$meal1Manager, $meal2Manager], $meals, $eventId, 
                $userId, $cid, $now1);
        $tickets2 = $citizen->getTicketsrestaurant();
        $this->assertEquals(2, count($tickets2));
        $ticket2_1 = $tickets2[0];
        $this->assertEquals("TK1", $ticket2_1->getName());
        $ticket2_2 = $tickets2[1];
        $this->assertEquals("TC1", $ticket2_2->getName());
        
        $now2 = new \DateTime("2020-04-01");
        $citizen->registerMeals([$meal1Manager, $meal2Manager], $meals, $eventId, 
                $userId, $cid, $now2);
        $tickets3 = $citizen->getTicketsrestaurant();
        $this->assertEquals(2, count($tickets3));
        
        $ticket3_1 = $tickets3[2];
        $this->assertEquals("TK2", $ticket3_1->getName());
        $ticket3_2 = $tickets3[1];
        $this->assertEquals("TC1", $ticket3_2->getName());
    }
}
