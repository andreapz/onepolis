<?php

namespace App\Controller;

use App\Entity\Citizen;
use App\Entity\ExtraCost;
use App\Entity\Hotel;
use App\Entity\HotelMatch;
use App\Entity\HotelReal;
use App\Entity\Room;
use App\Entity\RoomCostCitizen;
use App\Entity\RoomReal;
use App\Entity\RoomRealPrice;
use App\Form\Type\ExtraCostType;
use App\Form\Type\HotelRealType;
use App\Form\Type\MatchType;
use App\Form\Type\RoomRealPriceType;
use App\Form\Type\RoomRealType;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HotelRealController extends AbstractController {

    public function __construct(private ManagerRegistry $doctrine) {}
    
    /**
     * @Route("/admin/hotelreal/list/{id}", requirements={"id": "\d+"}, name="admin_hotel_real_index", methods={"GET"})
     * @Cache(smaxage="10")
     */
    public function indexAction($id) {
        $hotels = $this->doctrine->getRepository(HotelReal::class)->findByEvent($id);
        return $this->render('admin/hotelreal/index.html.twig', ['hotels' => $hotels, 'id' => $id]);
    }

    /**
     * Creates a new Post entity.
     *
     * @Route("/admin/hotelreal/new/{id}", requirements={"id": "\d+"}, name="admin_hotelreal_new", methods={"POST", "GET"})
     *
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function newAction($id, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $hotel = new HotelReal();
        $hotel->setEvent($id);

        return $this->form($hotel, $request);
    }

    /**
     * Creates a new Post entity.
     *
     * @Route("/admin/hotelreal/{id}", requirements={"id": "\d+"}, name="admin_hotelreal_show", methods={"POST", "GET"})
     *
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function showAction(HotelReal $hotel, Request $request) {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $rooms = array();
            foreach ($hotel->getHotel()->getRooms() as $roombase) {
                array_push($rooms, $roombase);    
            }
            
           // $form = $this->createForm(RoomRealType::class, $room,array('roomvirtuals' => $rooms));
        
        $form = $this->createForm(RoomRealType::class, new RoomReal(),
            array('action' => $this->generateUrl('admin_roomreal_new', ['id' => $hotel->getId()]),
                'roomvirtuals' => $rooms
                ));

        $formextra = $this->createForm(ExtraCostType::class, new ExtraCost(),
            array('action' => $this->generateUrl('admin_extracost_new', ['id' => $hotel->getId()])));

        $formroomprice = $this->createForm(RoomRealPriceType::class, new RoomRealPrice(),
                array('action' => $this->generateUrl('admin_roomrealprice_new', ['id' => $hotel->getId()])));

        return $this->render('admin/hotelreal/show.html.twig',
                ['hotel' => $hotel,
                 'form' => $form->createView(),
                 'formextra' => $formextra->createView(),
                 'formroomprice' => $formroomprice->createView()]);

    }

    /**
     * Creates a new Post entity.
     *
     * @Route("/admin/hotelreal/edit/{id}", requirements={"id": "\d+"}, name="admin_hotelreal_edit", methods={"POST", "GET"})
     *
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function editAction(HotelReal $hotel, Request $request) {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->form($hotel, $request);

    }

    public function form(HotelReal $hotel, Request $request) {
        $hotelVirtual = $hotel->getHotel();
        $hotelVirtuals = $this->doctrine->getRepository(Hotel::class)->findAll();

        $form = $this->createForm(HotelRealType::class, $hotel, array('hotels' => $hotelVirtuals));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($this->get('slugger')->slugify($post->getTitle()));

            // Hotel INIT
            $data = $form['hotels']->getData();

            $hotel->setHotel($data);

            // Hotel END

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($hotel);
            $entityManager->flush();

            $this->addFlash('success', 'hotel.created_successfully');

            /* if ($form->get('saveAndCreateNew')->isClicked()) {
              return $this->redirectToRoute('admin_events');
              } */

            return $this->redirectToRoute('admin_hotelreal_show', ['id' => $hotel->getId()]);
        }

        return $this->render('admin/hotelreal/new.html.twig', [
                    'hotel' => $hotel,
                    'form' => $form->createView(),
                    'h' => empty($hotelVirtual) ? 0 : $hotelVirtual->getId(),
        ]);
    }

    /**
     * @Route("/admin/hotelreal/search/{hotel}", requirements={"hotel": "\d+"}, name="hotelreal_handle_search", methods={"POST", "GET"})
     */
    public function searchRequest(Request $request, $hotel) {
        $em = $this->doctrine->getManager();
        $roomsfree = $em->getRepository(RoomReal::class)->findFreeByHotel($hotel);
        $result = array();

        foreach ($roomsfree as $room) {
            $hotelId = $room['hotel_real'];

            if (!isset($result[$hotelId])) {
                $hotel = $em->getRepository(HotelReal::class)->findNameByHotel($hotelId);
                $result[$hotelId] = array('rooms' => array(),
                    'name' => $hotel[0]['name'] . ' ' . $hotel[0]['surname']);
            }
            //var_dump($room['id']); die();
            $r = array('id' => $room['id'],
                    'name' => $room['name'],
                    'guests' => $room['guests'],
                    'single' => $room['single'],
                    'double' => $room['doublebed'],
                    'twin' => $room['twin'],
                    'sofa' => $room['sofa'],
                    'bunk' => $room['bunk'],
                    'bath' => $room['bath'],
                    'access' => $room['access'],
                    'html' => $this->renderView('admin/roomreal/_row.html.twig', array('room' => $room, 'show_control' => 0)));
            array_push($result[$hotelId]['rooms'], $r);
        }

        $data =  json_encode($result);
        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Route("/admin/hotelreal/match/{id}", requirements={"id": "\d+"}, name="admin_hotel_match", methods={"POST", "GET"})
     */
    public function matchAction($id, Request $request) {
        $hotels = $this->doctrine->getRepository(Hotel::class)->findByEvent($id);

        $postData = $request->request->get('match');
        $name_value = $postData['hotel'];

        $urltask = "";
        $urlhotel = "";

        if(!empty($name_value)) {
            $urltask = $this->generateUrl(
                        'task_handle_search',
                        array('event' => $id, 'hotel' => $name_value));
            $urlhotel = $this->generateUrl(
                        'hotelreal_handle_search',
                        array('hotel' => $name_value));
        } else {
            $name_value = 0;
        }
        //var_dump(empty($name_value)); die();

        //$form = $this->createForm(MatchType::class, new Hotel(), array('hotels' => $hotels));
        //$form->handleRequest($request);

        return $this->render('admin/hotelreal/match.html.twig', ['event' => $id,
            'hotels' => $hotels, 'value' => $name_value,
            'urlt' => $urltask, 'urlh' => $urlhotel]);
    }

    /**
     * @Route("/admin/hotelreal/matchauto/{event}/{hotel}", requirements={"event": "\d+", "hotel": "\d+"}, name="admin_hotel_match_auto", methods={"POST", "GET"})
     */
    public function matchAutoAction(Request $request, $event, $hotel)
    {
        $em = $this->doctrine->getManager();

        if ($hotel) {
            $data = $em->getRepository(Citizen::class)->findByHotel($event, $hotel);
        } else {
            $data = $em->getRepository(Citizen::class)->findAll();
        }

        $roomCostCitizenRepository = $em->getRepository(RoomCostCitizen::class);
        $roomRepository = $em->getRepository(Room::class);
        $rooms = array();
        $result[] = array();

        foreach ($data as $citizen) {

            $taskID = $citizen['task'];
            if (!isset($result[$taskID])) {
                $url = $this->generateUrl('task_show',array('id' => $taskID));
                $result[$taskID] = array();
                $result[$taskID]['url'] = $url;
            }

            $night = "";
            $roomCostCitizens = $roomCostCitizenRepository->findByCitizen($citizen['id']);

            foreach ($roomCostCitizens as $ticketsroom) {

                if (isset($rooms[$ticketsroom->getRid()])) {
                    $room = $rooms[$ticketsroom->getRid()];
                } else {
                    $room = $roomRepository->find($ticketsroom->getRid());
                    $rooms[$ticketsroom->getRid()] = $room;
                }

                $night = array('id' => $room->getId(), 'name' => $room->getName());
            }

            $birthDate = \DateTime::createFromFormat('Y-m-d H:i:s', $citizen['birth_date']);

            $age = $birthDate->diff(new \DateTime('now'));

            $result[$taskID]['citizen'][] = array('id' => $citizen['id'],
                        'name' => $citizen['name'],
                        'surname' => $citizen['surname'],
                        'support' => $citizen['need_support'],
                        'age' => $age->y,
                        'room_note' => $citizen['room_note'],
                        'room' => $night);
        }

        unset($result[0]);

        foreach ($result as $taskid => $task) {
            foreach ($task['citizen'] as $cid => $citizen) {
                var_dump($citizen['name']); die();
            }
        }

        
        //var_dump($result); die();
        $data =  json_encode($result);
        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Route("/admin/hotelreal/updateroomnote", name="admin_hotel_update_room_note", methods={"POST", "GET"})
     */
    public function updateRoomNoteAction(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $n = $request->request->get('n');
        $i = $request->request->get('i');

        if (empty($n) ||  empty($i)) {
            $result['status'] = 'ERROR';
            return $this->jsonReturn($result);
        }

        $entityManager = $this->doctrine->getManager();
        $h = $entityManager->getRepository(HotelMatch::class)->find($i);
        $h->setNote($n);
        $entityManager->persist($h);
        $entityManager->flush();
        
        $result['status'] = 'OK';
        return $this->jsonReturn($result);
    }

    /**
     * @Route("/admin/hotelreal/allocation", name="admin_hotel_allocation", methods={"POST", "GET"})
     */
    public function allocationAction(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $c = $request->request->get('c');
        $r = $request->request->get('r');
        $result = array();
        $user = $this->getUser();

        if (empty($c) || empty($r)) {
            $result['status'] = 'ERROR';
            return $this->jsonReturn($result);
        }

        $entityManager = $this->doctrine->getManager();
        foreach ($c as $citizen) {
            foreach ($r as $room) {
                $h = new HotelMatch();
                $h->setCitizen($citizen);
                $h->setRoomreal($room);
                $h->setD(0);
                $h->setLast(new \DateTime());
                $h->setUid($user->getId());
                $entityManager->persist($h);
                $entityManager->flush();
            }
        }



        $result['status'] = 'OK';
        return $this->jsonReturn($result);
    }

    /**
     * @Route("/admin/hotelreal/allocation/delete", name="admin_hotel_delete_allocation", methods={"POST", "GET"})
     */
    public function deleteAllocationAction(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $c = $request->request->get('c');
        $user = $this->getUser();

        $result = array();

        if (empty($c)) {
            $result['status'] = 'ERROR';
            return $this->jsonReturn($result);
        }
        $em = $this->doctrine->getManager();


        foreach ($c as $citizen) {
            $h = $em->getRepository(HotelMatch::class)->find($citizen);
            $h->setD(1);
            $h->setLast(new \DateTime());
            $h->setUid($user->getId());
            $em->persist($h);
            $em->flush();
        }

        $result['status'] = 'OK';
        return $this->jsonReturn($result);
    }

    private function jsonReturn($result) {
        $data =  json_encode($result);
        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Route("/admin/hotelreal/matched/{id}", requirements={"id": "\d+"}, name="admin_hotel_matched", methods={"POST", "GET"})
     */
    public function matchedAction($id, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $hotels = $this->doctrine->getRepository(Hotel::class)->findByEvent($id);

        $postData = $request->request->get('match');
        $name_value = $postData['hotel'];

        $urltask = "";
        $urlhotel = "";

        if(!empty($name_value)) {
            $urltask = $this->generateUrl(
                        'task_handle_search_allocation',
                        array('event' => $id, 'hotel' => $name_value, 'rendering' => 1));
        } else {
            $name_value = 0;
        }
        //var_dump(empty($name_value)); die();

        //$form = $this->createForm(MatchType::class, new Hotel(), array('hotels' => $hotels));
        //$form->handleRequest($request);

        return $this->render('admin/hotelreal/matched.html.twig', ['event' => $id,
            'hotels' => $hotels, 'value' => $name_value,
            'urlt' => $urltask]);
    }
    
    /**
     * @Route("/admin/hotelreal/allocationmap/{id}", requirements={"id": "\d+"}, name="admin_hotel_allocation_map", methods={"POST", "GET"})
     */
    public function allocationMapAction($id) {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $url = $this->generateUrl(
                        'task_handle_search_allocation_map',
                        array('event' => $id));
        
        return $this->render('admin/hotelreal/allocationmap.html.twig', ['event' => $id,
            'url' => $url]);
    }
    
    /**
     * @Route("/admin/hotelreal/searchallocation/{event}", requirements={"event": "\d+"}, name="task_handle_search_allocation_map", methods={"POST", "GET"})
     */
    public function searchAllocationMapRequest($event)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $data = $this->doctrine->getRepository(HotelReal::class)->findAllocationMap($event);
        $prices = $this->doctrine->getRepository(HotelReal::class)->findPrices($event);
        
        $result[] = array();
        $rate = array();
        
        foreach ($prices as $price) {
            $hotelId = $price['hrid'];
            if (!isset($rate[$hotelId])) {
                $rate[$hotelId] = array();
                $rate[$hotelId]['rooms'] = array();
                $rate[$hotelId]['name'] = $price['name'] . ' ' . $price['surname'];
            }
            
            $roomId = $price['rrid'];
            $roomRName = $price['rrname'];
            
            if (!isset($rate[$hotelId]['rooms'][$roomId])) {
                $rate[$hotelId]['rooms'][$roomId] = array();
                $rate[$hotelId]['rooms'][$roomId]['rrname'] = $roomRName;
                $rate[$hotelId]['rooms'][$roomId]['guests'] = $price['guests'];
                $rate[$hotelId]['rooms'][$roomId]['prices'] = array();
            }
            
            $rate[$hotelId]['rooms'][$roomId]['prices'][$price['person']] = $price['price'];
            
        }
        
        foreach ($data as $map) {
            $hotelId = $map['hrid'];
            if (!isset($result[$hotelId])) {
                $url = $this->generateUrl('admin_hotelreal_show',array('id' => $hotelId));
                $result[$hotelId] = array();
                $result[$hotelId]['url'] = $url;
                $result[$hotelId]['name'] = $map['hrname'] . ' ' . $map['hrsurname'];
                $result[$hotelId]['rooms'] = array();
                $result[$hotelId]['bundles'] = array();
            }
            
            $roomId = $map['rrid'];
            $roomRName = $map['rrname'];
            
            if (!isset($result[$hotelId]['rooms'][$roomId])) {
                $result[$hotelId]['rooms'][$roomId] = array();
                $result[$hotelId]['rooms'][$roomId]['rrname'] = $roomRName;
                $result[$hotelId]['rooms'][$roomId]['guests'] = $map['guests'];
                $result[$hotelId]['rooms'][$roomId]['reserved'] = 0;
                $result[$hotelId]['rooms'][$roomId]['transport'] = 0;
                $result[$hotelId]['rooms'][$roomId]['price'] = 0;
                $result[$hotelId]['rooms'][$roomId]['note'] = '';
                $result[$hotelId]['rooms'][$roomId]['idnote'] = $map['id'];
                //$result[$hotelId]['rooms'][$roomId]['rname'] = $map['rname'];
                $result[$hotelId]['rooms'][$roomId]['tasks'] = array();
            }
            
            $taskID = $map['task'];
            if (!isset($result[$hotelId]['rooms'][$roomId]['tasks'][$taskID])) {
                $url = $this->generateUrl('task_show',array('id' => $taskID));
                $result[$hotelId]['rooms'][$roomId]['tasks'][$taskID] = array();
                $result[$hotelId]['rooms'][$roomId]['tasks'][$taskID]['url'] = $url;
                $result[$hotelId]['rooms'][$roomId]['tasks'][$taskID]['citizens'] = array();
            }
            
            if (!isset($result[$hotelId]['rooms'][$roomId]['tasks'][$taskID]['citizens'][$map['cid']])) {
                
                $birthDate = \DateTime::createFromFormat('Y-m-d H:i:s', $map['cbirthdate']);
                $age = -1;
                if ($birthDate) {
                    $age = $birthDate->diff(new \DateTime('now'))->y;
                }
                $reserved = $result[$hotelId]['rooms'][$roomId]['reserved'] + 1;
                $result[$hotelId]['rooms'][$roomId]['reserved'] = $reserved;
                $price = 0;
                if (isset($rate[$hotelId]['rooms'][$roomId]['prices'][$reserved])) {
                    $price = $rate[$hotelId]['rooms'][$roomId]['prices'][$reserved];
                }
                $result[$hotelId]['rooms'][$roomId]['price'] = $price;
                
                $bundle = $map['rname'];
                $birtdate = '';

                if (isset($map['cbirthdate'])) {
                    $birtdate = \DateTime::createFromFormat('Y-m-d H:i:s', $map['cbirthdate'])->format('d/m/Y');;
                }
                
                $result[$hotelId]['rooms'][$roomId]['tasks'][$taskID]['citizens'][$map['cid']] = array('id' => $map['cid'],
                            'name' => $map['name'],
                            'surname' => $map['surname'],
                            'age' => $age,

                            'cbirthdate' => $birtdate,
                            'city_birth' => $map['city_birth'],
                            'cnote' => $map['cnote'] ? $map['cnote'] : '',
                            'roomnote' => $map['roomnote'] ? $map['roomnote'] : '',
                            'street' => $map['street'],
                            'city' => $map['city'],
                            'postcode' => $map['postcode'],
                            'province' => $map['province'],
                            'state' => $map['state'],
                            
                            'room' => $bundle,
                            'rid' => $map['rid'],
                            'transport' => $map['transport'] ? $map['transport'] : '',
                            'transports' => array(),
                            'meals' => array(),
                        //    'hotel' => $hotel
                        );
                $result[$hotelId]['rooms'][$roomId]['tasks'][$taskID]['citizens'][$map['cid']]['transports'][-1] = ' ';
                $result[$hotelId]['rooms'][$roomId]['tasks'][$taskID]['citizens'][$map['cid']]['meals'][-1] = ' ';

                $result[$hotelId]['rooms'][$roomId]['note'] .= $map['note']. " ";
                
                if ($map['transport'] && $map['transport'] == 1) {
                    $result[$hotelId]['rooms'][$roomId]['transport'] = $result[$hotelId]['rooms'][$roomId]['transport'] + 1;
                }
            }
            if ($map['restid']) {
                if (!isset($result[$hotelId]['rooms'][$roomId]['tasks'][$taskID]['citizens'][$map['cid']]['meals'][$map['restid']])) {
                    $result[$hotelId]['rooms'][$roomId]['tasks'][$taskID]['citizens'][$map['cid']]['meals'][$map['restid']] = $map['mealname'];
                }
            }
            
            if ($map['transport']) {
                if (!isset($result[$hotelId]['rooms'][$roomId]['tasks'][$taskID]['citizens'][$map['cid']]['transports'][$map['transport']])) {
                    $result[$hotelId]['rooms'][$roomId]['tasks'][$taskID]['citizens'][$map['cid']]['transports'][$map['transport']] = $map['busname'];
                }
            }
                
            if (!isset($result[$hotelId]['bundles'][$bundle])) {
                $result[$hotelId]['bundles'][$bundle]['rooms'] = array();
            }
            
            if (!isset($result[$hotelId]['bundles'][$bundle]['rooms'][$roomId])) {
                $result[$hotelId]['bundles'][$bundle]['rooms'][$roomId] = array();
                $result[$hotelId]['bundles'][$bundle]['rooms'][$roomId]['name'] = $roomRName;
                $result[$hotelId]['bundles'][$bundle]['rooms'][$roomId]['reserved'] = 1;
            } else {
                $result[$hotelId]['bundles'][$bundle]['rooms'][$roomId]['reserved'] += 1;
            }
            
            
        }
        
        foreach ($result[$hotelId]['bundles'] as $kb => $bundle) {
            
            foreach ($bundle['rooms'] as $room) {
                if (!isset($result[$hotelId]['bundles'][$kb]['guests'])) {
                    $result[$hotelId]['bundles'][$kb]['guests'] = array();
                }
                if (!isset($result[$hotelId]['bundles'][$kb]['guests'][$room['reserved']])) {
                    $result[$hotelId]['bundles'][$kb]['guests'][$room['reserved']] = 0;
                }
                    
                $result[$hotelId]['bundles'][$kb]['guests'][$room['reserved']] += 1;
                
            }
        }
        

        unset($result[0]);
        //var_dump($result); die();
        return new JsonResponse(json_encode($result), 200, [], true);
    }
}
