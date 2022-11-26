<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Branch;
use App\Entity\Event;
use App\Entity\Citizen;
use App\Entity\Hotel;
use App\Entity\HotelReal;
use App\Entity\Relationship;
use App\Entity\Room;
use App\Entity\RoomBase;
use App\Entity\RoomCost;
use App\Entity\RoomReal;
use App\Entity\Task;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $ueid = "1546058f-5a25-4334-85ae-e68f2a44bbaf";
        $uid = 1;
        
        $eventInit = "2020-04-25";
        $eventEnd = "2020-04-28";
        $taskOrderedDate = "2020-01-01";
        
        $manager->persist($this->createAddress('Lungomare Dante, 32', 'Alghero', '07041', 'SS','Italia'));
        $manager->flush();
        
        $address1 = $manager
            ->getRepository(Address::class)->findOneBy(array("street" => "Lungomare Dante, 32"));

        $manager->persist($this->createEvent($ueid, $eventInit, $eventEnd, $address1));
        $manager->flush();
        
        $event = $manager
            ->getRepository(Event::class)->findOneByUeid($ueid);
        
        //var_dump($eventSaved); die();
        $citizen = $this->createCitizen($event, $uid);
        $manager->persist($citizen);
        
        $task = $this->createTask($citizen, $event, $ueid, $uid, $taskOrderedDate);
        $manager->persist($task);
        
        $manager->persist($this->createRelationship("Nessuno"));
        $manager->persist($this->createRelationship("Padre"));
        $manager->persist($this->createRelationship("Madre"));
        
        $manager->persist($this->createBranch("Nessuno"));
        $manager->persist($this->createBranch("Aderenti adulti"));
        
        $manager->persist($this->createHotel('Laguna Blu', 'Case mobili, muratura e camper', $event));
        $manager->persist($this->createHotel('Hotel Balear', 'Hotel', $event));
        $manager->persist($this->createHotel('Nessuno', 'Pernottamento non utilizzato', $event));
        
        $manager->flush();
        
        $hotel1 = $manager->getRepository(Hotel::class)->findOneByName('Laguna Blu');
        $hotel2 = $manager->getRepository(Hotel::class)->findOneByName('Hotel Balear');
        $hotel3 = $manager->getRepository(Hotel::class)->findOneByName('Nessuno');
        
        $manager->persist($this->createHotelReal('Laguna Blu', $event->getId(), $hotel1));
        $manager->persist($this->createHotelReal('Hotel Balear', $event->getId(), $hotel2));
        $manager->persist($this->createHotelReal('Nessuno', $event->getId(), $hotel3));
        
        $manager->flush();
        
        $hotelReal1 = $manager->getRepository(HotelReal::class)->findOneByName('Laguna Blu');
        $hotelReal2 = $manager->getRepository(HotelReal::class)->findOneByName('Hotel Balear');
        $hotelReal2 = $manager->getRepository(HotelReal::class)->findOneByName('Nessuno');
        
        $total_mobile_q = 0;
        $days = 3;
        $day1 = '2020-04-25';
        $day2 = '2020-04-26';
        $day3 = '2020-04-27';
        $day4 = '2020-04-28';
        $dayInitEarly = '2019-12-01';
        $dayEndEarly = '2020-03-15';
        $dayInitLate = '2020-03-16';
        $dayFinish = '2020-04-30';
        $totalMobileQ = '200';
        $totalMobileD = '20';
        $totalMobileS = '6';
        $totalMuroD = '14';
        $totalMuroS = '3';
        $totalHotelT = '24';
        $totalHotelD = '34';
        $totalHotelS = '5';
        $totalNoHotelD = '100';
        $roomMinAdultAge = '10';
        $roomMaxChildrenAge = '9';
        
        $manager->persist($this->createRoomBase('Casa mobile Quadrupla angolo cottura', '3 notti', $totalMobileQ, 
                $days, $day1, $dayFinish, $event->getId(), $hotel1));
        $manager->persist($this->createRoomBase('Casa mobile Doppia', '3 notti', $totalMobileD, 
                $days, $day1, $dayFinish, $event->getId(), $hotel1));
        $manager->persist($this->createRoomBase('Camera singola', '3 notti', $totalMobileD, 
                $days, $day1, $dayFinish, $event->getId(), $hotel2));
        $manager->persist($this->createRoomBase('Camera doppia', '3 notti', $totalMobileD, 
                $days, $day1, $dayFinish, $event->getId(), $hotel2));
        $manager->persist($this->createRoomBase('-', '3 notti', $totalMobileD, 
                $days, $day1, $dayFinish, $event->getId(), $hotel3));
        $manager->flush();
        
        $roomBase1 = $manager
            ->getRepository(RoomBase::class)->findOneByName('Casa mobile Quadrupla angolo cottura');
        $roomBase2 = $manager
            ->getRepository(RoomBase::class)->findOneByName('Casa mobile Doppia');
        $roomBase3 = $manager
            ->getRepository(RoomBase::class)->findOneByName('Camera singola');
        $roomBase4 = $manager
            ->getRepository(RoomBase::class)->findOneByName('Camera doppia');
        $roomBase5 = $manager
            ->getRepository(RoomBase::class)->findOneByName('-');
        
        //    INSERT INTO `room_real` (`id`, `hotel_real`, `name`, `floor`, `rooms`, `guests`, `bath`, `access`, `single`, `doublebed`, 
//    `twin`, `sofa`, `bunk`, `room_base`) VALUES 

        
        //private function createRoomReal($room, $name, $floor, $rooms, $guests, $bath, 
          //  $accessible, $single, $double, $twin, $sofa, $bunk, $hotel) 
                
        $manager->persist($this->createRoomReal($roomBase1, 'LBM4401', 0,2,4,2,1,2,1,0,0,0, $hotelReal1));
        $manager->persist($this->createRoomReal($roomBase1, 'LBM4402', 0,2,4,2,1,2,1,0,0,0, $hotelReal1));
        $manager->persist($this->createRoomReal($roomBase1, 'LBM4403', 0,2,4,2,1,2,1,0,0,0, $hotelReal1));
        
        $manager->persist($this->createRoomReal($roomBase2, 'LBM2105', 0,1,2,1,1,2,0,0,0,0, $hotelReal1));
        $manager->persist($this->createRoomReal($roomBase2, 'LBM2106', 0,1,2,1,1,2,0,0,0,0, $hotelReal1));
        $manager->persist($this->createRoomReal($roomBase2, 'LBM2107', 0,1,2,1,1,2,0,0,0,0, $hotelReal1));
        
        $manager->persist($this->createRoomReal($roomBase3, 'EBS1',0,1,1,1,1,1,0,0,0,0, $hotelReal2));
        $manager->persist($this->createRoomReal($roomBase3, 'EBS2',0,1,1,1,1,1,0,0,0,0, $hotelReal2));
        $manager->persist($this->createRoomReal($roomBase3, 'EBS3',0,1,1,1,1,1,0,0,0,0, $hotelReal2));
        
        $manager->persist($this->createRoomReal($roomBase4, 'EBD1',0,1,2,1,1,2,0,0,0,0, $hotelReal2));
        $manager->persist($this->createRoomReal($roomBase4, 'EBD2',0,1,2,1,1,2,0,0,0,0, $hotelReal2));
        $manager->persist($this->createRoomReal($roomBase4, 'EBD3',0,1,2,1,1,2,0,0,0,0, $hotelReal2));
        
        
        $manager->persist($this->createRoom('Casa mobile Quadrupla angolo cottura -', '3 notti', $totalMobileQ, 
                $days, $day1, $dayFinish, $event->getId(), $hotel1->getId(), $roomBase1));
        $manager->persist($this->createRoom('Casa mobile Quadrupla angolo cottura mezza pensione', '3 notti', $totalMobileQ, 
                $days, $day1, $dayFinish, $event->getId(), $hotel1->getId(), $roomBase1));
        $manager->persist($this->createRoom('Casa mobile Quadrupla angolo cottura pensione completa', '3 notti', $totalMobileQ, 
                $days, $day1, $dayFinish, $event->getId(), $hotel1->getId(), $roomBase1));
        
        $manager->persist($this->createRoom('Casa mobile Doppia', '3 notti', $totalMobileD, 
                $days, $day1, $dayFinish, $event->getId(), $hotel1->getId(), $roomBase2));
        $manager->persist($this->createRoom('Casa mobile Doppia Mezza Pensione', '3 notti', $totalMobileD, 
                $days, $day1, $dayFinish, $event->getId(), $hotel1->getId(), $roomBase2));
        $manager->persist($this->createRoom('Casa mobile Doppia Pensione Completa', '3 notti', $totalMobileD, 
                $days, $day1, $dayFinish, $event->getId(), $hotel1->getId(), $roomBase2));
        $manager->persist($this->createRoom('Casa mobile Doppia uso singola', '3 notti', $totalMobileD, 
                $days, $day1, $dayFinish, $event->getId(), $hotel1->getId(), $roomBase2));
        
        $manager->persist($this->createRoom('Camera singola Pensione Completa', '3 notti', $totalHotelS, 
                $days, $day1, $dayFinish, $event->getId(), $hotel2->getId(), $roomBase3));
        $manager->persist($this->createRoom('Camera doppia Pensione Completa', '3 notti', $totalHotelD, 
                $days, $day1, $dayFinish, $event->getId(), $hotel2->getId(), $roomBase4));
        $manager->persist($this->createRoom('Nessun Pernottamento', '3 notti', "10000", 
                $days, $day1, $dayFinish, $event->getId(), $hotel3->getId(), $roomBase5));
        
        $manager->flush();
        
        $room1 = $manager
            ->getRepository(Room::class)->findOneByName('Casa mobile Quadrupla angolo cottura -');
        $room2 = $manager
            ->getRepository(Room::class)->findOneByName('Casa mobile Quadrupla angolo cottura mezza pensione');
        
        $room_max_age = '200';
        $room_min_adult_age = '10';
        $room_max_children_age = '9';
        $room_min_children_age = '0';
        $total_mobile_q = '200';
        $total_mobile_d = '20';
        $total_mobile_s = '6';
        $total_muro_d = '14';
        $total_muro_s = '3';
        $total_hotel_t = '24';
        $total_hotel_d = '34';
        $total_hotel_s ='5';
        $total_no_hotel_d = '100';
        
        $costo_evento_completo = 35;
        $costo_giornaliero = 12;
        $costo_giornaliero_2g = $costo_giornaliero * 2;
        $costo_bus = 12;
        $lb_singola = 60;
        $lb_camper = 20 * 3;
        $lb_camper_2 = 20 * 2;
        $lb_camper_1 = 20;
        $lb_pasto = 15;
        $lb_sconto_bimbi = 20;

        $lb_casa_mobile_quadrupla_pernotto_mezza_pensione = 135 - $costo_evento_completo - 3 * $lb_pasto;
        $lb_casa_mobile_quadrupla_pernotto_bimbi_mezza_pensione = $lb_casa_mobile_quadrupla_pernotto_mezza_pensione - $lb_sconto_bimbi;
        $lb_casa_mobile_quadrupla_pernotto_pensione_completa = 173 - $costo_evento_completo - 6 * $lb_pasto;
        $lb_casa_mobile_quadrupla_pernotto_bimbi_pensione_completa = $lb_casa_mobile_quadrupla_pernotto_pensione_completa - $lb_sconto_bimbi;

        $lb_casa_mobile_quadrupla_pernotto = 75 - $costo_evento_completo;
        $lb_casa_mobile_quadrupla_pernotto_bimbi = $lb_casa_mobile_quadrupla_pernotto - $lb_sconto_bimbi;

        /*INSERT INTO `room_cost` (`id`, `room`, `name`, `price`, `min_age`, `max_age`, `total`, `initial_date`, `end_date`, `eid`) VALUES
            (1, 1, 'Casa mobile Quadrupla', 'lb_casa_mobile_quadrupla_pernotto', room_min_adult_age, 200, total_mobile_q, 'day-init-early 00:00:00', 'day-finish 00:00:00', 1),
            (2, 1, 'Casa mobile Quadrupla bimbi', 'lb_casa_mobile_quadrupla_pernotto_bimbi', 0, room_max_children_age, total_mobile_q, 'day-init-early 00:00:00', 'day-finish 00:00:00', 1),
            (3, 2, 'Casa mobile Quadrupla mezza pensione', 'lb_casa_mobile_quadrupla_pernotto_mezza_pensione', room_min_adult_age, 200, total_mobile_q, 'day-init-early 00:00:00', 'day-finish 00:00:00', 1),
            (4, 2, 'Casa mobile Quadrupla bimbi mezza pensione', 'lb_casa_mobile_quadrupla_pernotto_bimbi_mezza_pensione', 0, room_max_children_age, total_mobile_q, 'day-init-early 00:00:00', 'day-finish 00:00:00', 1),
            (5, 3, 'Casa mobile Quadrupla pensione completa', 'lb_casa_mobile_quadrupla_pernotto_pensione_completa', room_min_adult_age, 200, total_mobile_q, 'day-init-early 00:00:00', 'day-finish 00:00:00', 1),
            (6, 3, 'Casa mobile Quadrupla bimbi pensione completa', 'lb_casa_mobile_quadrupla_pernotto_bimbi_pensione_completa', 0, room_max_children_age, total_mobile_q, 'day-init-early 00:00:00', 'day-finish 00:00:00', 1),
            
            (11, 10, 'Casa mobile Doppia', 'lb_casa_mobile_doppia_pernotto', room_min_adult_age, 200, total_mobile_d, 'day-init-early 00:00:00', 'day-finish 00:00:00', 1),
            (12, 10, 'Casa mobile Doppia bimbi', 'lb_casa_mobile_doppia_pernotto_bimbi', 0, room_max_children_age, total_mobile_d, 'day-init-early 00:00:00', 'day-finish 00:00:00', 1),
            (13, 11, 'Casa mobile Doppia Mezza Pensione', 'lb_casa_mobile_doppia_mezza_pensione', room_min_adult_age, 200, total_mobile_d, 'day-init-early 00:00:00', 'day-finish 00:00:00', 1),
            (14, 11, 'Casa mobile Doppia bimbi Mezza Pensione', 'lb_casa_mobile_doppia_mezza_pensione_bimbi', 0, room_max_children_age, total_mobile_d, 'day-init-early 00:00:00', 'day-finish 00:00:00', 1),
            (15, 12, 'Casa mobile Doppia Pensione Completa', 'lb_casa_mobile_doppia_pensione_completa', room_min_adult_age, 200, total_mobile_d, 'day-init-early 00:00:00', 'day-finish 00:00:00', 1),
            (16, 12, 'Casa mobile Doppia bimbi Pensione Completa', 'lb_casa_mobile_doppia_pensione_completa_bimbi', 0, room_max_children_age, total_mobile_d, 'day-init-early 00:00:00', 'day-finish 00:00:00', 1),
            
            (17, 10, 'Casa mobile Doppia uso singola', 'lb_casa_mobile_doppia_uso_singola_pernotto', 0, 200, total_mobile_d, 'day-init-early 00:00:00', 'day-finish 00:00:00', 1),
            
            (26, 50, 'Nessun Pernottamento', '0', 0, 200, 10000, 'day-init-early 00:00:00', 'day-finish 00:00:00', 1);
           */ 
        
        $manager->persist($this->createRoomCost('Casa mobile Quadrupla', $room1, $lb_casa_mobile_quadrupla_pernotto, $event->getId(), 
                $total_mobile_q, $room_max_age, $room_min_adult_age, $dayInitEarly, $dayEndEarly));
        $manager->persist($this->createRoomCost('Casa mobile Quadrupla Late', $room1, $lb_casa_mobile_quadrupla_pernotto, $event->getId(), 
                $total_mobile_q, $room_max_age, $room_min_adult_age, $dayInitLate, $dayFinish));
        $manager->persist($this->createRoomCost('Casa mobile Quadrupla bimbi', $room1, $lb_casa_mobile_quadrupla_pernotto_bimbi, $event->getId(), 
                $total_mobile_q, $room_max_children_age, $room_min_children_age, $dayInitEarly, $dayFinish));
        
        $manager->flush();
        
        
    }
    
    private function createRoomCost($name, $room, $price, $eid, $total, 
            $maxAge, $minAge, $initDate, $endDate) {
        $roomCost = new RoomCost();
        $roomCost->setEid($eid);
        $roomCost->setEndDate(new \DateTime($endDate));
        $roomCost->setInitialDate(new \DateTime($initDate));
        $roomCost->setMaxAge($maxAge);
        $roomCost->setMinAge($minAge);
        $roomCost->setName($name);
        $roomCost->setPrice($price);
        $roomCost->setRoom($room);
        $roomCost->setTotal($total);
        return $roomCost;
    }

    private function createRoom($name, $description, $total, $days, 
            $initDate, $endDate, $eid, $hid, $roomBase) {
        $room = new Room();
        $room->setDays($days);
        $room->setDescription($description);
        $room->setEid($eid);
        $room->setEndDate(new \DateTime($endDate));
        $room->setParent($roomBase);
        $room->setHid($hid);
        $room->setInitDate(new \DateTime($initDate));
        $room->setName($name);
        $room->setTotal($total);
        return $room;
    }
    
    

    private function createRoomReal($room, $name, $floor, $rooms, $guests, $bath, 
            $accessible, $single, $double, $twin, $sofa, $bunk, $hotel) {
        $roomReal = new RoomReal();
        $roomReal->setRoom($room);
        $roomReal->setName($name);
        
        $roomReal->setName($name);
        $roomReal->setFloor($floor);
        
        $roomReal->setRooms($rooms);
        $roomReal->setGuests($guests);
        $roomReal->setBath($bath);
        $roomReal->setAccessible($accessible);
        $roomReal->setSingle($single);
        $roomReal->setDouble($double);
        
        $roomReal->setTwin($twin);
        $roomReal->setSofa($sofa);
        $roomReal->setBunk($bunk);
        $roomReal->setHotel($hotel);
        return $roomReal;
    }
    
    private function createEvent($ueid, $eventInit, $eventEnd, $address) {
        $event = new Event();
        $event->setSlug("TX2020");
        $event->setUeid($ueid);
        $event->setTitle("TEST EVENT");
        $event->setInitialDate(new \DateTime($eventInit));
        $event->setEndDate(new \DateTime($eventEnd));
        $event->setAddress($address);
        return $event;
    }


    private function createTask($citizen, $event, $ueid, $uid, $orderedDate) {
        $task = new Task();
        $task->addCitizen($citizen);
        $task->setEvent($event);
        $task->setPayed(0);
        $task->setUeid($ueid);
        $task->setUid($uid);
        $task->setOrdered(0);
        $task->setAmount(0);
        $task->setOrderedDate(new \DateTime($orderedDate));
        return $task;
    }

    private function createCitizen($event, $uid) {
        $citizen = new Citizen();
        $citizen->setBirthDate(new \DateTime("2013-04-26"));
        $citizen->setName('Adele');
        $citizen->setSurname('Putzu');
        $citizen->setSurname('Putzu');
        $citizen->setNeedSupport(false);
        $citizen->setTransport(false);
        $citizen->setDelegate(0);
        $citizen->setGuest(0);
        $citizen->setPartner(0);
        $citizen->setDeleted(0);
        $citizen->setValid(true);
        $citizen->setFirst(true);
        $citizen->setEid($event->getId());
        $citizen->setUid($uid);
        return $citizen;
    }
    
    private function createRelationship($name) {
        $relationship = new Relationship();
        $relationship->setName($name);
        return $relationship;
    }
    
    private function createBranch($name) {
        $branch = new Branch();
        $branch->setName($name);
        return $branch;
    }
    
    private function createAddress($street, $city, $postcode, $province, $state) {
        
        $address = new Address();
        $address->setCity($city);
        $address->setPostcode($postcode);
        $address->setProvince($province);
        $address->setState($state);
        $address->setStreet($street);
        return $address;
    }
    
    private function createHotel($name, $description, $event) {
        $hotel = new Hotel();
        $hotel->setName($name);
        $hotel->setDescription($description);
        $hotel->setEvent($event);
        return $hotel;
    }
    
    private function createHotelReal($name, $event, $hotel) {
        $hotelReal = new HotelReal();
        $hotelReal->setName($name);
        $hotelReal->setEvent($event);
        $hotelReal->setHotel($hotel);
        return $hotelReal;
    }
    
    private function createRoomBase($name, $description, $total, $days, 
            $initDate, $endDate, $eid, $hotel) {
        $roomBase = new RoomBase();
        $roomBase->setDays($days);
        $roomBase->setDescription($description);
        $roomBase->setEid($eid);
        $roomBase->setEndDate(new \DateTime($endDate));
        $roomBase->setHotel($hotel);
        $roomBase->setInitDate(new \DateTime($initDate));
        $roomBase->setName($name);
        $roomBase->setTotal($total);
        return $roomBase;
    }
}
