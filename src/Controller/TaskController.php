<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use App\Controller\CitizenController;
use App\Entity\Branch;
use App\Entity\CheckIn;
use App\Entity\Citizen;
use App\Entity\CitizenPayment;
use App\Entity\Event;
use App\Entity\HotelMatch;
use App\Entity\Relationship;
use App\Entity\RestaurantCostCitizen;
use App\Entity\Room;
use App\Entity\RoomCostCitizen;
use App\Entity\RoomReal;
use App\Entity\Task;
use App\Entity\User;
use App\Repository\CitizenPaymentRepository;
use App\Repository\CitizenRepository;
use App\Repository\HotelMatchRepository;
use App\Repository\RestaurantCostCitizenRepository;
use App\Form\Type\CitizenPaymentType;
use App\Form\Type\TaskType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController {

    /**
     * @Route("/task/index/{ref}", defaults={"_format"="html"}, requirements={"ref": "\d+"}, name="task_index", methods={"GET"})
     */
    public function indexAction($ref, $_format) {
        $event = $this->doctrine->getRepository(Event::class)->find($ref);
        $tasks = $this->doctrine->getRepository(Task::class)->findAll($ref);
        //findLatest($page);
        // Every template name also has two extensions that specify the format and
        // engine for that template.
        // See https://symfony.com/doc/current/templating.html#template-suffix
        return $this->render('task/index.' . $_format . '.twig', ['tasks' => $tasks, 'event' => $event]);
    }

    /**
     * Creates a new Task entity.
     * @Security("is_authenticated()")
     * @Route("/task/{ueid}/new", name="task_new", methods={"POST", "GET"})
     *
     */
    public function newAction($ueid, Request $request) {
        $event = EventController::getEvent($this, $this->doctrine, $ueid);
        $citizen = new Citizen();
        $citizen->setDelegate(0);
        $citizen->setDeleted(0);
        $citizen->setValid(0);
        $citizen->setEid($event->getId());
        $task = new Task();
        $task->addCitizen($citizen);
        $task->setEvent($event);
        $task->setUeid($event->getUeid());
        
        return CitizenController::edit($this, $task, $citizen, $request, 0, 'citizen.created_successfully', false);
    }


    /**
     * Finds and displays a Event entity.
     * @Security("is_authenticated()")
     * @Route("/task/{id}", requirements={"id": "\d+"}, name="task_show", methods={"GET"})
     */
    public function showAction(Task $task) {
        // This security check can also be performed
        // using an annotation: @Security("is_granted('show', post)")
        //$this->denyAccessUnlessGranted('show', $post, 'Posts can only be shown to their authors.');
        //$interval = $event->getInitialDate()->diff($event->getEndDate());
        $addresses = array();
        $delegates = array();
        $ages = array();
        $branchmap = array();
        $relationmap = array();
        $restaurantes = array();
        $rooms = array();
                
        $branches = $this->doctrine->getRepository(Branch::class)->findAll();
        $relations = $this->doctrine->getRepository(Relationship::class)->findAll();
        $repository = $this->doctrine->getRepository(Citizen::class);
        $roomRepository = $this->doctrine->getRepository(RoomReal::class);
        
        foreach ($branches as $branche) {
            $branchmap[$branche->getId()] = $branche->getName();
        }

        foreach ($relations as $relation) {
            $relationmap[$relation->getId()] = $relation->getName();
        }

        foreach ($task->getCitizens() as $citizen) {
            if($citizen->getAddress()) {
                $addresses[$citizen->getAddress()->getId()] = $citizen->getAddress();
            }
            if ($citizen->getDelegate() > 0) {
                $delegate = $repository->findById($citizen->getDelegate());
                foreach ($delegate as $entity) {
                    $delegates[$citizen->getId()] = $entity->getName() . " " . $entity->getSurname();
                }
            }
            /*
            $dateNow = new \DateTime('now');
            $interval = $dateNow->diff($citizen->getBirthDate());
            $ages[$citizen->getId()] = $interval->format('%a');
            */

            $now = new \DateTime();
            $interval = $now->diff($citizen->getBirthDate());
            $ages[$citizen->getId()] = $interval->y;
            
            $citizenId = $citizen->getId();
            $data = $repository->findRestaurantMatchedByCitizen($citizenId);
            foreach ($data as $restaurantreal) {
                $restaurantes[$citizenId]['name'] = $restaurantreal['name'];
                $restaurantes[$citizenId]['surname'] = $restaurantreal['surname'];
                $restaurantes[$citizenId]['restaurantcost'] = $restaurantreal['restaurantcost'];
            }
            
            $dataroom = $roomRepository->findHotelByCitizen($citizenId);
            foreach ($dataroom as $hotelreal) {
                $rooms[$citizenId]['name'] = $hotelreal['name'];
                $rooms[$citizenId]['surname'] = $hotelreal['surname'];
                $rooms[$citizenId]['room'] = $hotelreal['room'];
            }
            
            //var_dump($rooms); die();
            /*
            $birthDate = \DateTime::createFromFormat('Y-m-d H:i:s', $citizen->getBirthDate());
            $ages[$citizen->getId()] = $birthDate->diff(new \DateTime('now'));
            */
        }

        $payments = $this->doctrine->getRepository(CitizenPayment::class)->findAllByTask($task->getId());
        $total = 0.0;
        foreach ($payments as $payment) {
            $total += $payment->getValue();
        }

        $payment = new CitizenPayment();
        $payment->setCode($task->getCode());

        $form = $this->createForm(CitizenPaymentType::class, $payment,
                array('action' => $this->generateUrl('admin_citizen_new_payment')));

        //var_dump($restaurantes); die();
        return $this->render('admin/task/show.html.twig', [
                    'task' => $task,
                    'addresses' => $addresses,
                    'payments' => $payments,
                    'total' => $total,
                    'form' => $form->createView(),
                    'diff' => $total - $task->getAmount(),
                    'ages' => $ages,
                    'delegates' => $delegates,
                    'branches' => $branchmap,
                    'relations' => $relationmap,
                    'restaurantes' => $restaurantes,
                    'rooms' => $rooms,
        ]);
    }

    /**
     * Finds and displays a Event entity.
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route(path="/tasks/{id}", requirements={"id": "\d+"}, name="task_show_all", methods={"GET"})
     */
    public function showAllAction(Event $event) {

        return $this->render('admin/task/showall.html.twig', [
                    'event' => $event
        ]);
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     *
     * @Route("/task/{id}/edit", requirements={"id": "\d+"}, name="admin_task_edit", methods={"POST", "GET"})
     */
    public function editAction(Task $task, Request $request) {
        //$this->denyAccessUnlessGranted('edit', $post, 'Posts can only be edited by their authors.');
//@Security("is_granted('ROLE_ADMIN')")
        $entityManager = $this->doctrine->getManager();

        $tickets = array();

        foreach ($task->getCitizens() as $tcitizen) {
            $citizenTickets = $this->doctrine->getRepository(RestaurantCostCitizen::class)->findByCitizenAndEvent($tcitizen->getId(), $task->getEvent());
            //$tickets[$citizen->getId()] = $citizenTickets;
            foreach ($citizenTickets as $citizenTicket) {
               /* if (empty($tickets[$tcitizen->getId()])) {
                    $tickets[$tcitizen->getId()] = array();
                }
                array_push($tickets[$tcitizen->getId()], $citizenTicket->getId());*/
                array_push($tickets, $citizenTicket->getId());
            }
        }

        $meals = $task->getRestaurantMeals();

        $form = $this->createForm(TaskType::class, $task, array('meals' => $meals));
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($this->get('slugger')->slugify($post->getTitle()));

            $data = $form['meals']->getData();


            $tickets = new ArrayCollection();

            $user = $this->getUser();

            foreach ($data as $entity) {
                $eid = $entity->getId();
                foreach ($event->getRestaurants() as $restaurant) {
                    foreach ($restaurant->getMeals() as $meal) {
                        if ($meal->getId() == 3333333) {
                            $userticket = new RestaurantCostCitizen();
                            $userticket->setName($ticket->getName());
                            $userticket->setPrice($ticket->getPrice());
                            $userticket->setBookDate($ticket->getBookDate());
                            $userticket->setType($ticket->getType());
                            $userticket->setOrderDate((new \DateTime()));
                            $userticket->setUid($user->getId());
                            $userticket->setRestaurantcost($ticket);
                            //$userticket->setCitizen($citizen);
                            $tickets->add($userticket);
                        }
                    }
                }
            }

            foreach ($task->getCitizens() as $tcitizen) {
                $tcitizen->setTask($task);
                $tcitizen->setTicketsrestaurant($tickets);
            }

            $task->setUid($user->getId());

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See http://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'restaurant.created_successfully');

            /* if ($form->get('saveAndCreateNew')->isClicked()) {
              return $this->redirectToRoute('admin_events');
              } */

            return $this->redirectToRoute('admin_events');
        }

        return $this->render('task/edit.html.twig', array(
                    'ids' => $tickets,
                    'post' => $task,
                    'form' => $form->createView(),
                    'm' => $meals,
        ));


    }


    /**
     * Creates a new Task entity.
     * @Security("is_authenticated()")
     * @Route("/task/{id}/order", requirements={"id": "\d+"}, name="task_order", methods={"POST", "GET"})
     *
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function orderAction(Task $task) {

        $task->setOrdered(1);
        $task->setOrderedDate(new \DateTime());
        $task->setCode($task->getEvent()->getSlug() . $task->getId());

        $amount = 0.0;

        foreach ($task->getCitizens() as $citizen) {
            
            if (!$citizen->getDeleted()) {
                $amount += floatval($citizen->getTicketsevent()->getPrice());

                foreach ($citizen->getTicketsrestaurant() as $restaurantCostCitizen) {
                    //echo floatval($restaurantCostCitizen->getPrice()) .  " ";
                    $amount += floatval($restaurantCostCitizen->getPrice());
                }

                foreach ($citizen->getTicketsroom() as $roomCostCitizen) {
                    //echo floatval($roomCostCitizen->getPrice()) .  " ";
                    $amount += floatval($roomCostCitizen->getPrice());
                }

                foreach ($citizen->getTicketsbus() as $busCostCitizen) {
                    //echo floatval($roomCostCitizen->getPrice()) .  " ";
                    $amount += floatval($busCostCitizen->getPrice());
                }
            }
        }

        $task->setAmount($amount);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('task_show', ['id' => $task->getId()]);

    }

    /**
     * Creates a new Task entity.
     * @Security("is_authenticated()")
     * @Route("/task/{id}/reset", requirements={"id": "\d+"}, name="task_reset_order", methods={"POST", "GET"})
     *
     */
    public function orderResetAction(Task $task) {

        $task->setOrdered(0);
        
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('task_show', ['id' => $task->getId()]);

    }

    /**
     * @Route("/task/search/{event}/{hotel}", requirements={"event": "\d+", "hotel": "\d+"}, name="task_handle_search", methods={"POST", "GET"})
     */
    public function searchRequest(Request $request, $event, $hotel)
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
        //var_dump($result); die();
        $data =  json_encode($result);
        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Route("/task/searchallocation/{event}/{hotel}/{rendering}", requirements={"event": "\d+", "hotel": "\d+", "rendering": "\d+"}, name="task_handle_search_allocation", methods={"POST", "GET"})
     */
    public function searchAllocationRequest($event, $hotel, $rendering)
    {
        $em = $this->doctrine->getManager();

        $data = $em->getRepository(Citizen::class)->findByMatch($hotel);

        $hotelMatchRepository = $em->getRepository(HotelMatch::class);
        $roomRealRepository = $em->getRepository(RoomReal::class);

        $rooms = array();
        $result[] = array();

        foreach ($data as $citizen) {

            $hotelId = $citizen['hrid'];

            if (!isset($result[$hotelId])) {
                $url = $this->generateUrl('admin_hotelreal_show',array('id' => $hotelId));
                $result[$hotelId] = array();
                $result[$hotelId]['url'] = $url;
                $result[$hotelId]['name'] = $citizen['hrname'] . ' ' . $citizen['hrsurname'];
                $result[$hotelId]['rooms'] = array();
            }

            $roomId = $citizen['hmmid'];

            if (!isset($result[$hotelId]['rooms'][$roomId])) {
                $result[$hotelId]['rooms'][$roomId] = array();
                $result[$hotelId]['rooms'][$roomId]['name'] = $citizen['hmmname'];
                $result[$hotelId]['rooms'][$roomId]['tasks'] = array();
                if ($rendering) {
                    $room = new RoomReal();
                    $room->setName($citizen['hmmname']);
                    $room->setRooms($citizen['hmmrooms']);
                    $room->setFloor($citizen['hmmfloor']);
                    $room->setGuests($citizen['hmmguests']);
                    $room->setBath($citizen['hmmbath']);
                    $room->setAccessible($citizen['hmmaccess']);
                    $room->setSingle($citizen['hmmsingle']);
                    $room->setDouble($citizen['hmmdouble']);
                    $room->setTwin($citizen['hmmtwin']);
                    $room->setSofa($citizen['hmmsofa']);
                    $room->setBunk($citizen['hmmbunk']);
                    $result[$hotelId]['rooms'][$roomId]['html'] = $this->renderView('admin/roomreal/_row.html.twig', array('room' => $room, 'show_control' => 0));
                }
            }

            $taskID = $citizen['task'];
            if (!isset($result[$hotelId]['rooms'][$roomId]['tasks'][$taskID])) {
                $url = $this->generateUrl('task_show',array('id' => $taskID));
                $result[$hotelId]['rooms'][$roomId]['tasks'][$taskID] = array();
                $result[$hotelId]['rooms'][$roomId]['tasks'][$taskID]['url'] = $url;
            }

            $birthDate = \DateTime::createFromFormat('Y-m-d H:i:s', $citizen['birth_date']);
            $age = $birthDate->diff(new \DateTime('now'));
                
            $result[$hotelId]['rooms'][$roomId]['tasks'][$taskID]['citizen'][] = array('id' => $citizen['id'],
                        'name' => $citizen['name'],
                        'surname' => $citizen['surname'],
                        'support' => $citizen['need_support'],
                        'hmid' => $citizen['hmmhmid'],
                        'age' => $age->y,
                        'note' => $citizen['room_note'],
                    //    'room' => $night,
                    //    'hotel' => $hotel
                    );
        }


        unset($result[0]);
        //var_dump($result); die();
        return new JsonResponse(json_encode($result), 200, [], true);
    }
    
    /**
     * @Route("/task/searchrestaurant/{event}/{restaurant}/{meal}", requirements={"event": "\d+", "restaurant": "\d+", "meal": "\d+"}, name="task_handle_search_restaurant", methods={"POST", "GET"})
     */
    public function searchRestaurantRequest(Request $request, $event, $restaurant, $meal)
    {
        $em = $this->doctrine->getManager();

        if ($meal == 0) {
            $data = $em->getRepository(Citizen::class)->findByRestaurant($restaurant);
        } else {
            $data = $em->getRepository(Citizen::class)->findByMeal($meal);
        }
        
        

        //$restaurantCostCitizenRepository = $em->getRepository(RestaurantCostCitizen::class);
        //$roomRepository = $em->getRepository(Room::class);
        //$meals = array();
        $result[] = array();

        foreach ($data as $citizen) {

            $taskID = $citizen['task'];
            if (!isset($result[$taskID])) {
                $url = $this->generateUrl('task_show',array('id' => $taskID));
                $result[$taskID]['citizen'] = array();
                $result[$taskID]['url'] = $url;
            }
            
            $citizenID = $citizen['id'];
            if (!isset($result[$taskID]['citizen'][$citizenID])) {
                $birthDate = \DateTime::createFromFormat('Y-m-d H:i:s', $citizen['birth_date']);
                $age = $birthDate->diff(new \DateTime('now'));
                $result[$taskID]['citizen'][$citizenID] = array('id' => $citizenID,
                        'name' => $citizen['name'],
                        'surname' => $citizen['surname'],
                        'support' => $citizen['need_support'],
                        'age' => $age->y,
                        'meals' => array());
            }

            $meal = array('id' => $citizen['mid'],
                    'name' => $citizen['mealname'],
                    'date' => $citizen['mealdate'],
                    'type' => $citizen['mtype']);
            
            array_push($result[$taskID]['citizen'][$citizenID]['meals'], $meal);
        }


        unset($result[0]);
        //var_dump($result); die();
        $data =  json_encode($result);
        return new JsonResponse($data, 200, [], true);
    }
    
    /**
     * @Route("/task/searchrestaurantallocation/{event}/{restaurant}", requirements={"event": "\d+", "restaurant": "\d+"}, name="task_handle_search_restaurant_allocation", methods={"POST", "GET"})
     */
    public function searchRestaurantAllocationRequest($event, $restaurant)
    {
        $em = $this->doctrine->getManager();

        $data = $em->getRepository(Citizen::class)->findByRestaurantMatch($restaurant);

        $result[] = array();

        foreach ($data as $citizen) {

            $taskID = $citizen['ctask'];
            if (!isset($result[$taskID])) {
                $url = $this->generateUrl('task_show',array('id' => $taskID));
                $result[$taskID] = array();
                $result[$taskID]['url'] = $url;
                $result[$taskID]['citizen'] = array();
            }

            $citizenid = $citizen['citizenid'];
            if (!isset($result[$taskID]['citizen'][$citizenid])) {
                $result[$taskID]['citizen'][$citizenid] = array('name' => $citizen['cname'],
                    'surname' => $citizen['csurname'],
                    'meals' => array());
            }
            
            $result[$taskID]['citizen'][$citizenid]['meals'][$citizen['rmid']] = array(
                'id' => $citizen['rmid'],
                'name' => $citizen['rrmname'],
                'type' => $citizen['rcctype'],
                'rdate' => $citizen['rccdate'],
                'rname' => $citizen['rrname'],
                'rsurname' => $citizen['rrsurname']
            );
        }

        unset($result[0]);
        return new JsonResponse(json_encode($result), 200, [], true);
    }

    /**
     * Finds and displays a Event entity.
     * @Security("is_authenticated()")
     * @Route("/tasks/showby/{id}", requirements={"id": "\d+"}, name="task_show_by_user", methods={"POST", "GET"})
     */
    public function showByUserAction($id, Request $request) {
        $userid = $this->getUser()->getId();
        $users = [$this->getUser()];
        $rooms = $this->doctrine->getRepository(Room::class)->findAll();
        
        $postData = $request->request->get('match');
        $roomid = $postData['room'];
            
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $users = $this->doctrine->getRepository(User::class)->findAll();
            $userid = $postData['user'];
        }
        
        $urltask = "";
       
        
        if (empty($roomid)) {
            $roomid = 0;
        }
        
        
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            if(empty($userid) || $userid == 0) {
                $urltask = $this->generateUrl('admin_tasks', array('id' => $id, 'r' => $roomid));
            } else {
                $urltask = $this->generateUrl('task_search_by_user', array('id' => $id, 'u' => $userid, 'r' => $roomid));
            }
        } else if(!empty($userid)) {
            $urltask = $this->generateUrl('task_search_by_user', array('id' => $id, 'u' => $userid, 'r' => $roomid));
        } 
        
        if(empty($userid)) {
            $userid = 0;
        }
        

        //$form = $this->createForm(MatchType::class, new Restaurant(), array('restaurants' => $restaurants));
        //$form->handleRequest($request);

        return $this->render('admin/task/showbyuser.html.twig', ['event' => $id,
            'users' => $users, 
            'userid' => $userid,
            'rooms' => $rooms,
            'roomid' => $roomid,
            'urlt' => $urltask]);
    }
    
    /**
     * Finds and displays a Event entity.
     * @Security("is_authenticated()")
     * @Route("/tasks/showbyadmin/{id}", requirements={"id": "\d+"}, name="task_show_by_admin", methods={"POST", "GET"})
     */
    public function showByAdminAction($id, Request $request) {
        $urltask = $this->generateUrl(
            'admin_tasks',
            array('id' => $id, 'r' => 0));

        return $this->render('admin/task/showbyadmin.html.twig', ['event' => $id,
            'urlt' => $urltask]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/task/list/{id}/{r}", requirements={"id": "\d+", "r": "\d+"}, name="admin_tasks", methods={"POST", "GET"})
     */
    public function tasksAction(Event $event, $r) {
       
        
        $citizens = $this->doctrine->getRepository(Citizen::class)
                ->findByAdmin($event->getId(), '', $r);
        
        $payments = $this->doctrine->getRepository(Task::class)
                ->findByAdmin($event->getId(), '', $r);

        $checkins = $this->doctrine->getRepository(CheckIn::class)
                ->findCheckins($event->getId());

        return $this->searchByUser($citizens, $payments, $checkins);
    }

    /**
     * Finds and displays a Event entity.
     *
     * @Route("/tasks/search/{id}/u/{u}/r/{r}", requirements={"id": "\d+", "u": "\d+", "r": "\d+"}, name="task_search_by_user", methods={"GET"})
     */
    public function searchByUserAction(Event $event, $u, $r) {
        if ($r > 0) {
            
        }
        $userid = $u;
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $userid = $this->getUser()->getId();
        }
        
        $citizens = $this->doctrine->getRepository(Citizen::class)
                ->findByUser($event->getId(), $userid, $r);
        
        $payments = $this->doctrine->getRepository(Task::class)
                ->findByUser($event->getId(), $userid, $r);
        
        $checkins = $this->doctrine->getRepository(CheckIn::class)
                ->findCheckins($event->getId());
       
        return $this->searchByUser($citizens, $payments, $checkins);
        
    }

    private function searchByUser($citizens, $payments, $checkins){

        $result[] = array();

        $result['hotels'] = array();
        $result['restaurantes'] = array();
        $result['buses'] = array();
        $userc = 0;

        $checkmap[] = array();
        
        foreach ($checkins as $map) {
            if (!isset($checkmap[$map['id']])) {
                $checkmap[$map['id']] = $map['type'];
            }
        }

        foreach ($citizens as $citizen) {

            $taskID = $citizen['task'];
            if (!isset($result['task'][$taskID])) {
                $url = $this->generateUrl('task_show',array('id' => $taskID));
                $result['task'][$taskID]['citizen'] = array();
                $result['task'][$taskID]['url'] = $url;
                $result['task'][$taskID]['payments'] = array();
                $result['task'][$taskID]['amount'] = $citizen['amount'];
                $result['task'][$taskID]['code'] = $citizen['code'];
                $result['task'][$taskID]['ordered'] = $citizen['ordered'];
                $result['task'][$taskID]['ordered_date'] = $citizen['ordered_date'];
            }
            
            $citizenID = $citizen['cid'];
            if (!isset($result['task'][$taskID]['citizen'][$citizenID])) {
                
                //$age = $citizen->getBirthDate()->diff(new \DateTime('now'));
                $userc++;

                $birthDate = \DateTime::createFromFormat('Y-m-d H:i:s', $citizen['birth_date']);
                $age = $birthDate->diff(new \DateTime('now'));
                
                $cki = isset($checkmap[$citizenID]) ? $checkmap[$citizenID] : 0;

                $result['task'][$taskID]['citizen'][$citizenID] = array('id' => $citizenID,
                        'name' => $citizen['name'],
                        'surname' => $citizen['surname'],
                        'birth_date' => $citizen['birth_date'],
                        'city_birth' => $citizen['city_birth'],
                        'phone' => $citizen['phone'],
                        'email' => $citizen['email'],
                        'gender' => $citizen['gender'],
                        'need_support' => $citizen['need_support'],
                        'note' => $citizen['note'],
                        'room_note' => $citizen['room_note'],                    
                        'street' => $citizen['street'],
                        'city' => $citizen['city'],
                        'postcode' => $citizen['postcode'],
                        'province' => $citizen['province'],
                        'state' => $citizen['state'],
                        'uid' => $citizen['uid'],
                        'username' => $citizen['username'],
                        'guest' => $citizen['guest'],
                        'branchname' => $citizen['branchname'],
                        'relationshipname' => $citizen['relationshipname'],
                        'roomname' => $citizen['roomname'],
                        'roomprice' => $citizen['roomprice'],
                        'buses' => array(),
                        'meals' => array(),
                        'age' => $age->y,
                        'cki' => $cki,
                        );
                
                $hotel = $citizen['hotel'];
                if ($hotel) {
                    if (!isset($result['hotels'][$hotel])) {
                        $result['hotels'][$hotel] = array();
                    }
                    $roomid = $citizen['roomid'];
                    if (!isset($result['hotels'][$hotel][$roomid])) {
                        $result['hotels'][$hotel][$roomid] = array();
                        $result['hotels'][$hotel][$roomid]['name'] = $citizen['roomname'];
                        $result['hotels'][$hotel][$roomid]['count'] = 1;
                    } else {
                        $c = $result['hotels'][$hotel][$roomid]['count'];
                        $result['hotels'][$hotel][$roomid]['count'] = $c + 1;
                    }
                }
            }
            
            if ($citizen['busname'] &&
                    !isset($result['task'][$taskID]['citizen'][$citizenID]['buses'][$citizen['busname']])) {
                $result['task'][$taskID]['citizen'][$citizenID]['buses'][$citizen['busname']] = $citizen['busprice'];
                
                if (!isset($result['buses'][$citizen['busname']])) {
                    $result['buses'][$citizen['busname']] = array();
                    $result['buses'][$citizen['busname']]['count'] = 0;
                }
                $result['buses'][$citizen['busname']]['count'] += 1;
            }

            $restaurant = $citizen['restaurant'];
                if ($restaurant) {
                    if (!isset($result['restaurantes'][$restaurant])) {
                        $result['restaurantes'][$restaurant] = array();
                    }
                    $mealid = $citizen['mealid'];
                    if (!isset($result['restaurantes'][$restaurant][$mealid])) {
                        $result['restaurantes'][$restaurant][$mealid] = array();
                        $d = '';
                        if ($citizen['mealdate'] && strlen ($citizen['mealdate']) >= 10) {
                            $d = substr($citizen['mealdate'], 0, 10);
                        }
                        $result['restaurantes'][$restaurant][$mealid]['name'] = $citizen['mealname'] . ' ' . $d;
                        $result['restaurantes'][$restaurant][$mealid]['count'] = 1;
                    } else {
                        $c = $result['restaurantes'][$restaurant][$mealid]['count'];
                        $result['restaurantes'][$restaurant][$mealid]['count'] = $c + 1;
                    }
                }
            
            if (isset($citizen['restccid'])) {
                $restccid = $citizen['restccid'];
                $result['task'][$taskID]['citizen'][$citizenID]['meals'][$restccid] = array('price' => $citizen['mealprice'], 'name' => $citizen['mealname'], 'mealdate' => $citizen['mealdate']);
            }
        }

        foreach ($payments as $payment) {

            $taskID = $payment['tid'];
            if (!isset($result['task'][$taskID])) {
                $url = $this->generateUrl('task_show',array('id' => $taskID));
                $result['task'][$taskID]['citizen'] = array();
                $result['task'][$taskID]['url'] = $url;
                $result['task'][$taskID]['payments'] = array();
            }
            
            
            if (isset($payment['cpid'])) {
                $cpid = $payment['cpid'];
                if (!isset($result['task'][$taskID]['payments'][$cpid])) {
                    $result['task'][$taskID]['payments'][$cpid] = array(
                        'value' => $payment['value'],
                        'description' => $payment['description'],
                        'type' => $payment['type'],
                        'payment_date' => $payment['payment_date'],
                        );
                }
            }
            
        }

        $result['users']['tot'] = $userc;

        unset($result[0]);
        //var_dump($result); die();
        $data =  json_encode($result);
        return new JsonResponse($data, 200, [], true);
    }
}
