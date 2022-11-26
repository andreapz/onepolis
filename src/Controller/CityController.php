<?php

namespace App\Controller;

use App\Entity\Cap;
use App\Entity\City;
use App\Repository\CapRepository;
use App\Form\Type\CityType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use App\Entity\HotelReal;
use App\Entity\RoomReal;
use App\Entity\RoomRealPrice;

class CityController extends AbstractController {

    /**
     * @Route("/city/handleSearch/{_query?}", name="city_handle_search", methods={"POST", "GET"})
     */
    public function handleSearchRequest(Request $request, $_query) {
        $em = $this->doctrine->getManager();
        if ($_query) {
            $data = $em->getRepository(City::class)->findByName($_query);
        } else {
            $data = $em->getRepository(City::class)->findAll();
        }
        // setting up the serializer 
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->serialize($data, 'json');
        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Route("/city/{id?}", name="city_page", methods={"GET"})
     */
    public function citySingle(Request $request, $id) {
        $em = $this->doctrine->getManager();
        $city = null;

        if ($id) {
            $city = $em->getRepository(Cities::class)->findOneBy(['id' => $id]);
        }
        return $this->render('home/city.html.twig', [
                    'city' => $city
        ]);
    }

    /*     *
     * Displays a form to edit an existing Task entity.
     *
     *
     * @ Route("/admin/city/update", requirements={"id": "\d+"}, name="update_cap")
     * @ Method({"GET", "POST"})

      public function updateCapAction(Request $request) {

      $repository = $this->doctrine->getRepository(City::class);
      $repositoryCap = $this->doctrine->getRepository(Cap::class);
      $cities = $repository->findAll();

      $entityManager = $this->doctrine->getManager();

      foreach ($cities as $city) {
      if ($city->getCap() == NULL) {
      $caps = $repositoryCap->findByCode($city->getCC());
      //var_dump($cap); die();
      if ($caps) {
      foreach ($caps as $cap) {
      $city->setCap($cap->getCap());
      $entityManager->persist($city);
      $entityManager->flush();
      }
      }
      }
      }

      return $this->render('admin/address/add.html.twig', [
      'form' => $form->createView(),
      ]);
      }
     */

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/admin/templatesql", name="templatesql", methods={"GET"})
     */
    public function templatesqlAction(Request $request) {
        $sql = "
            INSERT INTO `relationship` VALUES (1,'Nessuno','Nessuno'),(2,'Padre','Padre'),(3,'Madre','Madre'),(4,'Figlio','Figlio'),(5,'Figlia','Figlia'),(6,'Fratello','Fratello'),(7,'Sorella','Sorella'),(8,'Marito','Marito'),(9,'Moglie','Moglie'),(10,'Nipote','Nipote'),(11,'Nonno','Nonno'),(12,'Nonna','Nonna');

            INSERT INTO `branch` VALUES (1,'Nessuno',NULL),(2,'Aderenti adulti',NULL),(3,'Famiglie',NULL),(4,'Focolarini/e',NULL),(5,'Focolarini/e sposati',NULL),(6,'GEN2',NULL),(7,'GEN3',NULL),(8,'GEN4',NULL),(9,'GEN5',NULL),(10,'GENRE',NULL),(11,'GENS',NULL),(12,'Mariapolita',NULL),(13,'Parrocchie',NULL),(14,'Religiosi/e',NULL),(15,'Sacerdoti',NULL),(16,'Vescovi',NULL),(17,'Volontari/e',NULL);

            INSERT INTO `address` VALUES 
            (1,'Lungomare Dante, 32','Alghero','07041','SS','Italia'),
            (2,'Strada Statale 127 bis, km 41','Alghero','07041','SS','Italia'),
            (3,'Via Garibaldi 1','Alghero','07041','SS','Italia');
            
            INSERT INTO `event` VALUES (1,1,'1546058f-5a25-4334-85ae-e68f2a44bbaf', 'Test Event','TEX', 'day1 00:00:00','day-finish 00:00:00');

            INSERT INTO `hotel` (`id`, `event`, `name`, `description`) VALUES
            (1, 1, 'Laguna Blu', 'Case mobili, muratura e camper'),
            (2, 1, 'Hotel Balear', 'Hotel'),
            (3, 1, 'Nessuno', 'Pernottamento non utilizzato');
        
            INSERT INTO `room_base` (`id`, `hotel`, `name`, `description`, `total`, `days`, `init_date`, `end_date`, `eid`) VALUES
            
            (1, 1, 'Casa mobile Quadrupla angolo cottura', '3 notti', total_mobile_q, 3, 'day1 00:00:00', 'day-finish 12:00:00', 1),
            (2, 1, 'Casa mobile Doppia', '3 notti', total_mobile_d, 3, 'day1 00:00:00', 'day-finish 12:00:00', 1),
           
            (4, 2, 'Camera singola', 'Camera doppia', 'total_hotel_s', 1, 'day1 00:00:00', 'day-finish 12:00:00', 1),
            (5, 2, 'Camera doppia', 'Camera doppia', 'total_hotel_d', 1, 'day1 00:00:00', 'day-finish 12:00:00', 1),
            (6, 3, '-', 'Hotel non necessario', 'total_no_hotel_d', 3, 'day1 00:00:00', 'day-finish 12:00:00', 1),
                
            INSERT INTO `room` (`id`, `room_base`, `hid`, `name`, `description`, `total`, `days`, `init_date`, `end_date`, `eid`) VALUES
            (1, 1, 1, 'Casa mobile Quadrupla angolo cottura', '3 notti', total_mobile_q, 3, 'day1 00:00:00', 'day-finish 12:00:00', 1),
            (2, 1, 1, 'Casa mobile Quadrupla angolo cottura mezza pensione', '3 notti', total_mobile_q, 3, 'day1 00:00:00', 'day-finish 12:00:00', 1),
            (3, 1, 1, 'Casa mobile Quadrupla angolo cottura pensione completa', '3 notti', total_mobile_q, 3, 'day1 00:00:00', 'day-finish 12:00:00', 1),
            (10, 2, 1, 'Casa mobile Doppia', '3 notti', total_mobile_d, 3, 'day1 00:00:00', 'day-finish 12:00:00', 1),
            (11, 2, 1, 'Casa mobile Doppia Mezza Pensione', '3 notti', total_mobile_d, 3, 'day1 00:00:00', 'day-finish 12:00:00', 1),
            (12, 2, 1, 'Casa mobile Doppia Pensione Completa', '3 notti', total_mobile_d, 3, 'day1 00:00:00', 'day-finish 12:00:00', 1),
            (13, 2, 1, 'Casa mobile Doppia uso singola', '3 notti', total_mobile_d, 3, 'day1 00:00:00', 'day-finish 12:00:00', 1),
            
            (30, 4, 2, 'Camera singola Pensione Completa', 'Camera doppia', 'total_hotel_s', 1, 'day1 00:00:00', 'day-finish 12:00:00', 1),
            (40, 5, 2, 'Camera doppia Pensione Completa', 'Camera doppia', 'total_hotel_d', 1, 'day1 00:00:00', 'day-finish 12:00:00', 1),
            (50, 6, 3, 'Nessun Pernottamento', 'Nessun Pernottamento', 10000, 1000, 'day1 00:00:00', 'day-finish 12:00:00', 1);
            
            INSERT INTO `room_cost` (`id`, `room`, `name`, `price`, `min_age`, `max_age`, `total`, `initial_date`, `end_date`, `eid`) VALUES
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
            
            INSERT INTO `room_meal` (`id`, `room`, `meal`, `status`, `event`) VALUES
            (1, 3, 1, 's', 1),
            (2, 3, 3, 's', 1),
            (3, 3, 5, 's', 1);
           
            INSERT INTO `hotel_real` VALUES 
            (1,1,1,'Camping Village Laguna Blu',NULL,'079 930111',NULL,'40.595097','8.2889646','prova',1),
            (2,2,2,'Hotel El Balear',NULL,'079975229','info@hotelelbalear.it','40.5524106','8.3171995',NULL,1),
            (3,NULL,3,'Nessun Pernottamento',NULL,NULL,NULL,NULL,NULL,NULL,1);
				

            INSERT INTO `room_real` (`id`, `hotel_real`, `name`, `floor`, `rooms`, `guests`, `bath`, `access`, `single`, `doublebed`, `twin`, `sofa`, `bunk`, `room_base`) VALUES 
            (1,1,'LBM4401',0,2,4,2,1,2,1,0,0,0, 1),
            (2,1,'LBM4402',0,2,4,2,1,2,1,0,0,0, 1),
            (3,1,'LBM4403',0,2,4,2,1,2,1,0,0,0, 1),
            

            (20,1,'LBM2105',0,1,2,1,1,2,0,0,0,0, 2),
            (21,1,'LBM2106',0,1,2,1,1,2,0,0,0,0, 2),
            (22,1,'LBM2107',0,1,2,1,1,2,0,0,0,0, 2),
            (23,1,'LBM2108',0,1,2,1,1,2,0,0,0,0, 2),
            (24,1,'LBM2109',0,1,2,1,1,2,0,0,0,0, 2),
            (25,1,'LBM2110',0,1,2,1,1,2,0,0,0,0, 2),
            

            (40,2,'EBS1',0,1,1,1,1,1,0,0,0,0, 4),
            (41,2,'EBS2',0,1,1,1,1,1,0,0,0,0, 4),
            (42,2,'EBS3',0,1,1,1,1,1,0,0,0,0, 4),
            

            (50,2,'EBD1',0,1,2,1,1,2,0,0,0,0, 5),
            (51,2,'EBD2',0,1,2,1,1,2,0,0,0,0, 5),
            (52,2,'EBD3',0,1,2,1,1,2,0,0,0,0, 5),
            (53,2,'EBD4',0,1,2,1,1,2,0,0,0,0, 5),
            (54,2,'EBD5',0,1,2,1,1,2,0,0,0,0, 5),
            (55,2,'EBD6',0,1,2,1,1,2,0,0,0,0, 5),
            
            (100,3,'nessuna stanza',0,0,0,0,0,0,0,0,0,0, 6);

            INSERT INTO `restaurant` (`id`, `event`, `name`, `description`) VALUES
            (1, 1, 'Laguna Blu', 'pasti a menu fisso'),
            (2, 1, 'Hotel Balear', 'pasti a menu fisso');

            INSERT INTO `restaurant_meal` (`id`, `restaurant`, `name`, `meal_date`, `type`, `total`, `eid`) VALUES
            (1, 1, 'Laguna Blu Cena', 'day1 00:00:00', 'cena', 400, 1),
            (2, 1, 'Laguna Blu Pranzo', 'day2 00:00:00', 'pranzo', 400, 1),
            (3, 1, 'Laguna Blu Cena', 'day2 00:00:00', 'cena', 400, 1),
            (4, 1, 'Laguna Blu Pranzo', 'day3 00:00:00', 'pranzo', 400, 1),
            (5, 1, 'Laguna Blu Cena', 'day3 00:00:00', 'cena', 400, 1),
            (6, 1, 'Laguna Blu Pranzo', 'day4 00:00:00', 'pranzo', 400, 1),
            (7, 2, 'Hotel Balear Cena', 'day1 00:00:00', 'cena', 400, 1),
            (8, 2, 'Hotel Balear Pranzo', 'day2 00:00:00', 'pranzo', 400, 1),
            (9, 2, 'Hotel Balear Cena', 'day2 00:00:00', 'cena', 400, 1),
            (10, 2, 'Hotel Balear Pranzo', 'day3 00:00:00', 'pranzo', 400, 1),
            (11, 2, 'Hotel Balear Cena', 'day3 00:00:00', 'cena', 400, 1),
            (12, 2, 'Hotel Balear Pranzo', 'day4 00:00:00', 'pranzo', 400, 1),
            (13, 1, 'Laguna Blu Pranzo al sacco', 'day2 00:00:00', 'pranzo', 400, 1),
            (14, 1, 'Laguna Blu Pranzo al sacco', 'day3 00:00:00', 'pranzo', 400, 1),
            (15, 1, 'Laguna Blu Pranzo al sacco', 'day4 00:00:00', 'pranzo', 400, 1),
            (16, 2, 'Hotel Balear Pranzo al sacco', 'day2 00:00:00', 'pranzo', 400, 1),
            (17, 2, 'Hotel Balear Pranzo al sacco', 'day3 00:00:00', 'pranzo', 400, 1),
            (18, 2, 'Hotel Balear Pranzo al sacco', 'day4 00:00:00', 'pranzo', 400, 1);

            INSERT INTO `restaurant_cost` (`id`, `restaurant_meal`, `name`, `price`, `min_age`, `max_age`, `total`, `book_init_date`, `book_end_date`, `type`) VALUES
            (1, 1, 'Menu fisso', 'lb_pasto', 0, 200, 200, 'day-init-early 00:00:00', 'day-finish 00:00:00', 'cena'),
            (2, 2, 'Menu fisso', 'lb_pasto', 0, 200, 200, 'day-init-early 00:00:00', 'day-finish 00:00:00', 'pranzo'),
            (3, 3, 'Menu fisso', 'lb_pasto', 0, 200, 200, 'day-init-early 00:00:00', 'day-finish 00:00:00', 'cena'),
            (4, 4, 'Menu fisso', 'lb_pasto', 0, 200, 200, 'day-init-early 00:00:00', 'day-finish 00:00:00', 'pranzo'),
            (5, 5, 'Menu fisso', 'lb_pasto', 0, 200, 200, 'day-init-early 00:00:00', 'day-finish 00:00:00', 'cena'),
            (6, 6, 'Menu fisso', 'lb_pasto', 0, 200, 200, 'day-init-early 00:00:00', 'day-finish 00:00:00', 'pranzo'),
            (7, 7, 'Menu fisso', 'hotel_pasto', 0, 200, 200, 'day-init-early 00:00:00', 'day-finish 00:00:00', 'cena'),
            (8, 8, 'Menu fisso', 'hotel_pasto', 0, 200, 200, 'day-init-early 00:00:00', 'day-finish 00:00:00', 'pranzo'),
            (9, 9, 'Menu fisso', 'hotel_pasto', 0, 200, 200, 'day-init-early 00:00:00', 'day-finish 00:00:00', 'cena'),
            (10, 10, 'Menu fisso', 'hotel_pasto', 0, 200, 200, 'day-init-early 00:00:00', 'day-finish 00:00:00', 'pranzo'),
            (11, 11, 'Menu fisso', 'hotel_pasto', 0, 200, 200, 'day-init-early 00:00:00', 'day-finish 00:00:00', 'cena'),
            (12, 12, 'Menu fisso', 'hotel_pasto', 0, 200, 200, 'day-init-early 00:00:00', 'day-finish 00:00:00', 'pranzo'),       
            (13, 13, 'Menu fisso', 'lb_pasto', 0, 200, 200, 'day-init-early 00:00:00', 'day-finish 00:00:00', 'pranzo'),
            (14, 14, 'Menu fisso', 'lb_pasto', 0, 200, 200, 'day-init-early 00:00:00', 'day-finish 00:00:00', 'pranzo'),
            (15, 15, 'Menu fisso', 'lb_pasto', 0, 200, 200, 'day-init-early 00:00:00', 'day-finish 00:00:00', 'pranzo'),
            (16, 16, 'Menu fisso', 'hotel_pasto', 0, 200, 200, 'day-init-early 00:00:00', 'day-finish 00:00:00', 'pranzo'),
            (17, 17, 'Menu fisso', 'hotel_pasto', 0, 200, 200, 'day-init-early 00:00:00', 'day-finish 00:00:00', 'pranzo'),
            (18, 18, 'Menu fisso', 'hotel_pasto', 0, 200, 200, 'day-init-early 00:00:00', 'day-finish 00:00:00', 'pranzo');


INSERT INTO `restaurant_real` (`id`, `address_id`, `restaurant`, `name`, `surname`, `phone`, `email`, `latitude`, `longitude`, `note`, `event`) VALUES

            (1,1,1,'Camping Village Laguna Blu',NULL,'079 930111',NULL,'40.595097','8.2889646','prova',1),
            (2,2,2,'Hotel El Balear',NULL,'079975229','info@hotelelbalear.it','40.5524106','8.3171995',NULL,1);
			

            INSERT INTO `restaurant_real_meal` (`id`, `restaurant_real`, `name`, `guests`, `rid`, `mealid`) VALUES
            (1, 1, 'Laguna Blu Cena day1', 400, 1, 1),
            (2, 1, 'Laguna Blu Pranzo day2', 400, 1, 2),
            (3, 1, 'Laguna Blu Cena day2', 400, 1, 3),
            (4, 1, 'Laguna Blu Pranzo day3', 400, 1, 4),
            (5, 1, 'Laguna Blu Cena day3', 400, 1, 5),
            (6, 1, 'Laguna Blu Pranzo day4', 400, 1, 6),
            (7, 2, 'Hotel Balear Cena day1', 400, 1, 7),
            (8, 2, 'Hotel Balear Pranzo day2', 400, 1, 8),
            (9, 2, 'Hotel Balear Cena day2', 400, 1, 9),
            (10, 2, 'Hotel Balear Pranzo day3', 400, 1, 10),
            (11, 2, 'Hotel Balear Cena day3', 400, 1, 11),
            (12, 2, 'Hotel Balear Pranzo day4', 400, 1, 12),
            (13, 1, 'Laguna Blu Pranzo al sacco day2', 400, 1, 13),
            (14, 1, 'Laguna Blu Pranzo al sacco day3', 400, 1, 14),
            (15, 1, 'Laguna Blu Pranzo al sacco day4', 400, 1, 15),
            (16, 2, 'Hotel Balear Pranzo al sacco day2', 400, 1, 16),
            (17, 2, 'Hotel Balear Pranzo al sacco day3', 400, 1, 17),
            (18, 2, 'Hotel Balear Pranzo al sacco day4', 400, 1, 18);
            

            INSERT INTO `ticket_type` (`id`, `event`, `name`, `description`, `total`, `days`, `init_date`, `end_date`) VALUES
            (1, 1, 'Evento completo', 'Evento completo', 10000, 4, 'day1 00:00:00', 'day-finish 00:00:00'),
            (2, 1, 'Pendolari 1 giorno', 'pendolari 1 giorno', 1000, 1, 'day1 00:00:00', 'day-finish 00:00:00'),
            (3, 1, 'Pendolari 2 giorni', 'pendolari 2 giorni', 1000, 2, 'day1 00:00:00', 'day-finish 00:00:00');

            INSERT INTO `ticket_cost` (`id`, `ticket_type`, `name`, `price`, `min_age`, `max_age`, `total`, `book_init_date`, `book_end_date`) VALUES
            (1, 1, 'Evento completo', 'costo_evento_completo', 0, 200, 1000, 'day-init-early 00:00:00', 'day-finish 00:00:00'),
            (2, 2, 'Evento pendolare adulti 1 giorno', 'costo_giornaliero', 0, 200, 1000, 'day-init-early 00:00:00', 'day-finish 00:00:00'),
            (3, 3, 'Evento pendolare adulti 2 giorni', 'costo_giornaliero_2g', 0, 200, 1000, 'day-init-early 00:00:00', 'day-finish 00:00:00');

            INSERT INTO `transport` (`id`, `event`, `name`, `description`) VALUES 
            ('1', '1', 'Bus navetta', 'Bus navetta'),
            ('2', '1', 'Bus escursione', 'Bus escursione');

            INSERT INTO `bus` (`id`, `transport`, `name`, `description`, `total`, `days`, `init_date`, `end_date`) VALUES 
            ('1', '1', 'Bus navetta', 'Bus navetta', '300', '3', 'day-init-early 00:00:00', 'day-finish 00:00:00'),
            ('2', '2', 'Bus escursione', 'Bus escursione', '300', '3', 'day-init-early 00:00:00', 'day-finish 00:00:00');

            INSERT INTO `bus_cost` (`id`, `bus`, `name`, `price`, `min_age`, `max_age`, `total`, `initial_date`, `end_date`) VALUES 
            ('1', '1', 'Bus navetta', 'costo_bus', '0', '200', '300', 'day-init-early 00:00:00', 'day-finish 00:00:00'),
            (2, 2, 'Bus escursione', '5', 0, 200, 300, 'day-init-early 00:00:00', 'day-finish 00:00:00');

            
            ";



        $clear_online = "
            SET FOREIGN_KEY_CHECKS = 0; 
            TRUNCATE table citizen_payment;
            TRUNCATE table ticket_cost_citizen;
            TRUNCATE table ticket_cost; 
            TRUNCATE table ticket_type; 
            TRUNCATE table room_meal;
            TRUNCATE table room_cost_citizen;
            TRUNCATE table room_cost;
            TRUNCATE table room;
            TRUNCATE table hotel;
            TRUNCATE table restaurant_cost_citizen;
            TRUNCATE table restaurant_cost;
            TRUNCATE table restaurant_meal;
            TRUNCATE table restaurant;            
            TRUNCATE table bus_cost;
            TRUNCATE table bus;
            TRUNCATE table transport;
            SET FOREIGN_KEY_CHECKS = 1;
            ";

        $clear_localhost = "
            DELETE FROM citizen_payment;
            DELETE FROM ticket_cost_citizen;
            DELETE FROM ticket_cost; 
            DELETE FROM ticket_type; 
            DELETE FROM room_meal;
            DELETE FROM room_cost_citizen;
            DELETE FROM room_cost;
            DELETE FROM room;
            DELETE FROM hotel;
            DELETE FROM restaurant_cost_citizen;
            DELETE FROM restaurant_cost;
            DELETE FROM restaurant_meal;
            DELETE FROM restaurant;            
            DELETE FROM bus_cost;
            DELETE FROM bus;
            DELETE FROM transport;
            ";

        $other = "
            UPDATE  task SET ordered = 0 WHERE id = 13;
            UPDATE  task SET ordered = 0 WHERE id > 0;



            INSERT INTO `restaurant_real` (`id`, `address_id`, `restaurant`, `name`, `surname`, `phone`, `email`, `latitude`, `longitude`, `note`, `event`) VALUES
            (1, 21, 1, 'Ristorante', 'Enhorabona', '+39 079 989 3078', NULL, NULL, NULL, NULL, 1);


            INSERT INTO `restaurant_real_meal` (`id`, `restaurant_real`, `name`, `guests`, `rid`) VALUES
            (1, 1, 'Cena', 6, 1),
            (2, 1, 'Pranzo', 10, 1);

            INSERT INTO `restaurant_real_meal_price` (`id`, `restaurant_real_meal`, `price`, `guests`) VALUES
            (1, 1, '10.00', 10),
            (2, 2, '10.00', 10);
            </p>
            ";

        $sql = str_replace('day1', '2020-04-25', $sql);
        $sql = str_replace('day2', '2020-04-26', $sql);
        $sql = str_replace('day3', '2020-04-27', $sql);
        $sql = str_replace('day4', '2020-04-28', $sql);
        $sql = str_replace('day-init-early', '2019-12-01', $sql);
        $sql = str_replace('day-end-early', '2020-03-15', $sql);
        $sql = str_replace('day-init-late', '2020-03-16', $sql);
        $sql = str_replace('day-finish', '2020-04-30', $sql);
        $sql = str_replace('total_mobile_q', '200', $sql);
        $sql = str_replace('total_mobile_d', '20', $sql);
        $sql = str_replace('total_mobile_s', '6', $sql);
        $sql = str_replace('total_muro_d', '14', $sql);
        $sql = str_replace('total_muro_s', '3', $sql);
        $sql = str_replace('total_hotel_t', '24', $sql);
        $sql = str_replace('total_hotel_d', '34', $sql);
        $sql = str_replace('total_hotel_s', '5', $sql);
        $sql = str_replace('total_no_hotel_d', '100', $sql);
        $sql = str_replace('room_min_adult_age', '10', $sql);
        $sql = str_replace('room_max_children_age', '9', $sql);


        $costo_evento_completo = 35;
        $costo_giornaliero = 12;
        $costo_giornaliero_2g = $costo_giornaliero * 2;
        $costo_bus = 12;

        $sql = str_replace('costo_evento_completo', $costo_evento_completo, $sql);
        $sql = str_replace('costo_giornaliero_2g', $costo_giornaliero_2g, $sql);
        $sql = str_replace('costo_giornaliero', $costo_giornaliero, $sql);
        $sql = str_replace('costo_bus', $costo_bus, $sql);

        $lb_singola = 60;
        $lb_camper = 20 * 3;
        $lb_camper_2 = 20 * 2;
        $lb_camper_1 = 20;
        $lb_pasto = 15;
        $lb_sconto_bimbi = 20;

        $sql = str_replace('lb_pasto', $lb_pasto, $sql);

        // CASA MOBILE
        $lb_casa_mobile_doppia_pernotto = 90 - $costo_evento_completo;
        $lb_casa_mobile_doppia_mezza_pensione = 150 - $costo_evento_completo - 3 * $lb_pasto;
        $lb_casa_mobile_doppia_pensione_completa = 188 - $costo_evento_completo - 6 * $lb_pasto;

        $lb_casa_mobile_doppia_uso_singola_pernotto = 150 - $costo_evento_completo;

        $lb_casa_mobile_doppia_pernotto_bimbi = $lb_casa_mobile_doppia_pernotto - $lb_sconto_bimbi;
        $lb_casa_mobile_doppia_mezza_pensione_bimbi = $lb_casa_mobile_doppia_mezza_pensione - $lb_sconto_bimbi;
        $lb_casa_mobile_doppia_pensione_completa_bimbi = $lb_casa_mobile_doppia_pensione_completa - $lb_sconto_bimbi;

        $lb_casa_mobile_singola_pernotto = $lb_casa_mobile_doppia_pernotto + $lb_singola;
        $lb_casa_mobile_singola_mezza_pensione = $lb_casa_mobile_doppia_mezza_pensione + $lb_singola;
        $lb_casa_mobile_singola_pensione_completa = $lb_casa_mobile_doppia_pensione_completa + $lb_singola;

        $lb_casa_mobile_quadrupla_uso_doppio_pernotto = 95 - $costo_evento_completo;
        $lb_casa_mobile_quadrupla_uso_doppio_pernotto_bimbi = $lb_casa_mobile_quadrupla_uso_doppio_pernotto - $lb_sconto_bimbi;

        $lb_casa_mobile_quadrupla_uso_doppio_pernotto_mezza_pensione = 155 - $costo_evento_completo - 3 * $lb_pasto;
        $lb_casa_mobile_quadrupla_uso_doppio_pernotto_bimbi_mezza_pensione = $lb_casa_mobile_quadrupla_uso_doppio_pernotto_mezza_pensione - $lb_sconto_bimbi;
        $lb_casa_mobile_quadrupla_uso_doppio_pernotto_pensione_completa = 195 - $costo_evento_completo - 6 * $lb_pasto;
        $lb_casa_mobile_quadrupla_uso_doppio_pernotto_bimbi_pensione_completa = $lb_casa_mobile_quadrupla_uso_doppio_pernotto_pensione_completa - $lb_sconto_bimbi;

        $lb_casa_mobile_quadrupla_pernotto_mezza_pensione = 135 - $costo_evento_completo - 3 * $lb_pasto;
        $lb_casa_mobile_quadrupla_pernotto_bimbi_mezza_pensione = $lb_casa_mobile_quadrupla_pernotto_mezza_pensione - $lb_sconto_bimbi;
        $lb_casa_mobile_quadrupla_pernotto_pensione_completa = 173 - $costo_evento_completo - 6 * $lb_pasto;
        $lb_casa_mobile_quadrupla_pernotto_bimbi_pensione_completa = $lb_casa_mobile_quadrupla_pernotto_pensione_completa - $lb_sconto_bimbi;

        $lb_casa_mobile_quadrupla_pernotto = 75 - $costo_evento_completo;
        $lb_casa_mobile_quadrupla_pernotto_bimbi = $lb_casa_mobile_quadrupla_pernotto - $lb_sconto_bimbi;

        // CASA MURATURA
        $lb_casa_muratura_doppia_pernotto = 120 - $costo_evento_completo;
        $lb_casa_muratura_doppia_mezza_pensione = 180 - $costo_evento_completo - 3 * $lb_pasto;
        ;
        $lb_casa_muratura_doppia_pensione_completa = 218 - $costo_evento_completo - 6 * $lb_pasto;

        $lb_casa_muratura_doppia_pernotto_bimbi = $lb_casa_muratura_doppia_pernotto - $lb_sconto_bimbi;
        $lb_casa_muratura_doppia_mezza_pensione_bimbi = $lb_casa_muratura_doppia_mezza_pensione - $lb_sconto_bimbi;
        $lb_casa_muratura_doppia_pensione_completa_bimbi = $lb_casa_muratura_doppia_pensione_completa - $lb_sconto_bimbi;

        $lb_casa_muratura_singola_pernotto = $lb_casa_muratura_doppia_pernotto + $lb_singola;
        $lb_casa_muratura_singola_mezza_pensione = $lb_casa_muratura_doppia_mezza_pensione + $lb_singola;
        $lb_casa_muratura_singola_pensione_completa = $lb_casa_muratura_doppia_pensione_completa + $lb_singola;

        //HOTEL
        $hotel_pasto = 18;
        $hotel_singola = 60;

        $sql = str_replace('hotel_pasto', $hotel_pasto, $sql);

        $hotel_camera_tripla_pensione_completa = 253 - $costo_evento_completo - 6 * $hotel_pasto;
        $hotel_camera_doppia_pensione_completa = 270 - $costo_evento_completo - 6 * $hotel_pasto;
        $hotel_camera_singola_pensione_completa = $hotel_camera_doppia_pensione_completa + $hotel_singola;

        $hotel_camera_tripla_pensione_completa_bimbi = $hotel_camera_tripla_pensione_completa - $lb_sconto_bimbi;
        $hotel_camera_doppia_pensione_completa_bimbi = $hotel_camera_doppia_pensione_completa - $lb_sconto_bimbi;
        $hotel_camera_singola_pensione_completa_bimbi = $hotel_camera_singola_pensione_completa - $lb_sconto_bimbi;

        $sql = str_replace('lb_camper_1', $lb_camper_1, $sql);
        $sql = str_replace('lb_camper_2', $lb_camper_2, $sql);
        $sql = str_replace('lb_camper', $lb_camper, $sql);

        $sql = str_replace('lb_casa_mobile_doppia_pernotto_bimbi', $lb_casa_mobile_doppia_pernotto_bimbi, $sql);
        $sql = str_replace('lb_casa_mobile_doppia_mezza_pensione_bimbi', $lb_casa_mobile_doppia_mezza_pensione_bimbi, $sql);
        $sql = str_replace('lb_casa_mobile_doppia_pensione_completa_bimbi', $lb_casa_mobile_doppia_pensione_completa_bimbi, $sql);

        $sql = str_replace('lb_casa_mobile_doppia_pernotto', $lb_casa_mobile_doppia_pernotto, $sql);
        $sql = str_replace('lb_casa_mobile_doppia_mezza_pensione', $lb_casa_mobile_doppia_mezza_pensione, $sql);
        $sql = str_replace('lb_casa_mobile_doppia_pensione_completa', $lb_casa_mobile_doppia_pensione_completa, $sql);

        $sql = str_replace('lb_casa_mobile_doppia_uso_singola_pernotto', $lb_casa_mobile_doppia_uso_singola_pernotto, $sql);

        $sql = str_replace('lb_casa_mobile_singola_pernotto', $lb_casa_mobile_singola_pernotto, $sql);
        $sql = str_replace('lb_casa_mobile_singola_mezza_pensione', $lb_casa_mobile_singola_mezza_pensione, $sql);
        $sql = str_replace('lb_casa_mobile_singola_pensione_completa', $lb_casa_mobile_singola_pensione_completa, $sql);


        $sql = str_replace('lb_casa_mobile_quadrupla_uso_doppio_pernotto_bimbi_pensione_completa', $lb_casa_mobile_quadrupla_uso_doppio_pernotto_bimbi_pensione_completa, $sql);
        $sql = str_replace('lb_casa_mobile_quadrupla_uso_doppio_pernotto_pensione_completa', $lb_casa_mobile_quadrupla_uso_doppio_pernotto_pensione_completa, $sql);
        $sql = str_replace('lb_casa_mobile_quadrupla_uso_doppio_pernotto_bimbi_mezza_pensione', $lb_casa_mobile_quadrupla_uso_doppio_pernotto_bimbi_mezza_pensione, $sql);
        $sql = str_replace('lb_casa_mobile_quadrupla_uso_doppio_pernotto_mezza_pensione', $lb_casa_mobile_quadrupla_uso_doppio_pernotto_mezza_pensione, $sql);
        $sql = str_replace('lb_casa_mobile_quadrupla_uso_doppio_pernotto_bimbi', $lb_casa_mobile_quadrupla_uso_doppio_pernotto_bimbi, $sql);
        $sql = str_replace('lb_casa_mobile_quadrupla_uso_doppio_pernotto', $lb_casa_mobile_quadrupla_uso_doppio_pernotto, $sql);

        $sql = str_replace('lb_casa_mobile_quadrupla_pernotto_bimbi_pensione_completa', $lb_casa_mobile_quadrupla_pernotto_bimbi_pensione_completa, $sql);
        $sql = str_replace('lb_casa_mobile_quadrupla_pernotto_pensione_completa', $lb_casa_mobile_quadrupla_pernotto_pensione_completa, $sql);
        $sql = str_replace('lb_casa_mobile_quadrupla_pernotto_bimbi_mezza_pensione', $lb_casa_mobile_quadrupla_pernotto_bimbi_mezza_pensione, $sql);
        $sql = str_replace('lb_casa_mobile_quadrupla_pernotto_mezza_pensione', $lb_casa_mobile_quadrupla_pernotto_mezza_pensione, $sql);
        $sql = str_replace('lb_casa_mobile_quadrupla_pernotto_bimbi', $lb_casa_mobile_quadrupla_pernotto_bimbi, $sql);
        $sql = str_replace('lb_casa_mobile_quadrupla_pernotto', $lb_casa_mobile_quadrupla_pernotto, $sql);

        $sql = str_replace('lb_casa_muratura_doppia_pernotto_bimbi', $lb_casa_muratura_doppia_pernotto_bimbi, $sql);
        $sql = str_replace('lb_casa_muratura_doppia_mezza_pensione_bimbi', $lb_casa_muratura_doppia_mezza_pensione_bimbi, $sql);
        $sql = str_replace('lb_casa_muratura_doppia_pensione_completa_bimbi', $lb_casa_muratura_doppia_pensione_completa_bimbi, $sql);

        $sql = str_replace('lb_casa_muratura_doppia_pernotto', $lb_casa_muratura_doppia_pernotto, $sql);
        $sql = str_replace('lb_casa_muratura_doppia_mezza_pensione', $lb_casa_muratura_doppia_mezza_pensione, $sql);
        $sql = str_replace('lb_casa_muratura_doppia_pensione_completa', $lb_casa_muratura_doppia_pensione_completa, $sql);

        $sql = str_replace('lb_casa_muratura_singola_pernotto', $lb_casa_muratura_singola_pernotto, $sql);
        $sql = str_replace('lb_casa_muratura_singola_mezza_pensione', $lb_casa_muratura_singola_mezza_pensione, $sql);
        $sql = str_replace('lb_casa_muratura_singola_pensione_completa', $lb_casa_muratura_singola_pensione_completa, $sql);

        $sql = str_replace('hotel_camera_tripla_pensione_completa_bimbi', $hotel_camera_tripla_pensione_completa_bimbi, $sql);
        $sql = str_replace('hotel_camera_doppia_pensione_completa_bimbi', $hotel_camera_doppia_pensione_completa_bimbi, $sql);
        $sql = str_replace('hotel_camera_singola_pensione_completa_bimbi', $hotel_camera_singola_pensione_completa_bimbi, $sql);

        $sql = str_replace('hotel_camera_tripla_pensione_completa', $hotel_camera_tripla_pensione_completa, $sql);
        $sql = str_replace('hotel_camera_doppia_pensione_completa', $hotel_camera_doppia_pensione_completa, $sql);
        $sql = str_replace('hotel_camera_singola_pensione_completa', $hotel_camera_singola_pensione_completa, $sql);


        // setting up the serializer 
        /* $encoders = array(new JsonEncoder());
          $normalizers = array(new ObjectNormalizer());

          $serializer = new Serializer($normalizers, $encoders);
          $data = $serializer->serialize($sql, 'json');
          return new JsonResponse($data, 200, [], true); */

        return $this->render('admin/templatesql/new.html.twig', [
                    'templatesql' => $sql,
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/admin/createRoomRealPrice", name="create_room_real_price", methods={"GET"})
     */
    public function createRoomRealPriceAction(Request $request) {
        $em = $this->doctrine->getManager();
        $rooms = $this->doctrine->getRepository(RoomReal::class)->findAll();
        $result = array();
        $entityManager = $this->doctrine->getManager();

        $ELM = 80;
        $ELD = 80;
        $ELT = 105;
        $LBQ = 13;

        foreach ($rooms as $room) {

            $price = 13; //€ 13,00 a persona al giorno sistemazione in case mobili triple/quadruple con angolo cottura solo pernottamento 

            if (strpos($room->getName(), 'LBM2') !== false) {
                $price = 16; // € 16,00 a persona al giorno sistemazione in  case mobili senza angolo cottura occupate da 2 persone solo pernottamento
            } else if (strpos($room->getName(), 'LBC2') !== false) {
                $price = 27; // € 27,00 a persona al giorno in sistemazione camera doppia in muratura occupate da 2 persone solo pernottamento
            } else if (strpos($room->getName(), 'EBS') !== false) {
                $price = 60; // Camera Singola Standard in Bed & Breakfast: €. 60,00 per persona al giorno;
            } else if (strpos($room->getName(), 'EBD') !== false) {
                $price = $ELD; // Camera Matrimoniale o Doppia in Bed & Breakfast: €. 85,00 a camera al giorno;
            } else if (strpos($room->getName(), 'EBM') !== false) {
                $price = $ELM; // Camera Matrimoniale o Doppia in Bed & Breakfast: €. 85,00 a camera al giorno;
            } else if (strpos($room->getName(), 'EBT') !== false) {
                $price = $ELT; // Camera Tripla in Bed & Breakfast: €. 110,00 a camera al giorno;
            }


            for ($i = 1; $i <= $room->getGuests(); $i++) {

                $cost = $price * $i;

                if ($i == 1) {
                    if (strpos($room->getName(), 'LBM2') !== false) {
                        $cost = 24; // € 24,00 a persona al giorno sistemazione in casa mobile senza angolo cottura occupata da 1 persona solo pernottamento
                    } else if (strpos($room->getName(), 'LBC2') !== false) {
                        $cost = 34.5; // € 34,50 a persona al giorno in sistemazione camera doppia uso singola in muratura occupata da 1 persona solo pernottamento
                    }
                } else if ($i == 2) {
                    if (strpos($room->getName(), 'EBD') !== false) {
                        $cost = $ELD; // Camera Matrimoniale o Doppia in Bed & Breakfast: €. 85,00 a camera al giorno;
                    } else if (strpos($room->getName(), 'EBM') !== false) {
                        $cost = $ELM; // Camera Matrimoniale o Doppia in Bed & Breakfast: €. 85,00 a camera al giorno;
                    } else if (strpos($room->getName(), 'EBT') !== false) {
                        $cost = $ELT; // Camera Tripla in Bed & Breakfast: €. 110,00 a camera al giorno;
                    }
                } else if ($i == 3) {
                    if (strpos($room->getName(), 'EBT') !== false) {
                        $cost = $ELT; // Camera Tripla in Bed & Breakfast: €. 110,00 a camera al giorno;
                    }
                }

                $rp = new RoomRealPrice();
                $rp->setPrice($cost * 3); //3 nights
                $rp->setGuests($i);
                $rp->setRoom($room);

                $entityManager->persist($rp);
                $entityManager->flush();
            }
        }


        return $this->redirectToRoute('admin_hotel_real_index', ['id' => '1']);
    }

    /*     *
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/admin/updateRoomRealPrice", name="update_room_real_price", methods={"GET"})

      public function updateRoomRealPriceAction(Request $request)
      {
      $em = $this->doctrine->getManager();
      $rooms = $this->doctrine->getRepository(RoomRealPrice::class)->findGuests(4);
      $result = array();
      $entityManager = $this->doctrine->getManager();

      foreach ($rooms as $room) {

      $price = 13; //€ 13,00 a persona al giorno sistemazione in case mobili triple/quadruple con angolo cottura solo pernottamento

      $cost = $price * 5;

      $rp = new RoomRealPrice();
      $rp->setPrice($cost * 3); //3 nights
      $rp->setGuests(5);
      $rp->setRoom($room->getRoom());

      $entityManager->persist($rp);
      $entityManager->flush();

      $cost = $price * 6;

      $rp = new RoomRealPrice();
      $rp->setPrice($cost * 3); //3 nights
      $rp->setGuests(6);
      $rp->setRoom($room->getRoom());

      $entityManager->persist($rp);
      $entityManager->flush();


      }


      return $this->redirectToRoute('admin_hotel_real_index', ['id' => '1']);
      }
     */



//€ 18,00 a persona al giorno sistemazione in  case mobili con angolo cottura occupate da 2 persone solo pernottamento



    /*
      EBS1 Camera Singola Standard in Bed & Breakfast: €. 60,00 per persona al giorno;
      EBD1  EBM1 Camera Matrimoniale o Doppia in Bed & Breakfast: €. 85,00 a camera al giorno;
      EBT1 Camera Tripla in Bed & Breakfast: €. 110,00 a camera al giorno;
      Supplemento Mezza Pensione o Pensione Completa: €. 18,00 per persona a pasto;
      25 Camere tra Doppie, matrimoniali o Triple e di 3/5 Camere Singole Standard (1 letto 90x200).
     */
}
