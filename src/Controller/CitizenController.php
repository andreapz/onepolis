<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use App\Entity\Branch;
use App\Entity\Bus;
use App\Entity\BusCostCitizen;
use App\Entity\CheckIn;
use App\Entity\Citizen;
use App\Entity\CitizenPayment;
use App\Entity\Event;
use App\Entity\Relationship;
use App\Entity\RestaurantCost;
use App\Entity\RestaurantCostCitizen;
use App\Entity\RestaurantMeal;
use App\Entity\Room;
use App\Entity\RoomBase;
use App\Entity\RoomMeal;
use App\Entity\RoomCostCitizen;
use App\Entity\TicketCost;
use App\Entity\TicketCostCitizen;
use App\Entity\TicketType;
use App\Entity\Task;
use App\Repository\RestaurantCostCitizenRepository;
use App\Form\Type\CitizenType;
use App\Form\Type\CitizenPaymentType;
use App\Form\Type\TaskType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

class CitizenController extends AbstractController {
    public function __construct(private ManagerRegistry $doctrine) {}

    public static function edit($instance, $doctrine, Task $task, Citizen $citizen, Request $request, $cid, $msg, $persistCitizen) {
        $meals = $task->getRestaurantMeals();
        $rooms = $task->getHotelRooms();
        $buses = $task->getTransportBuses();
        $ticketTypes = $task->getTickets();

        $repositoryBranch = $doctrine->getRepository(Branch::class);
        $branches = $repositoryBranch->findAll();
        $repositoryRelationship = $doctrine->getRepository(Relationship::class);
        $relationships = $repositoryRelationship->findAll();
        $repository = $doctrine->getRepository(Citizen::class);
        $delegateName = "";

        $eventId = $task->getEvent()->getId();
        $jMealsCount = RestaurantMeal::checkMealsCount($doctrine->getRepository(RestaurantMeal::class), $eventId);
        $jRoomsCount = Room::checkRoomsCount($doctrine->getRepository(Room::class), $eventId);
        $jRoomMeals = RoomMeal::checkRoomMeals($doctrine->getRepository(RoomMeal::class), $eventId);

        if ($citizen->getDelegate() > 0) {
            $delegates = $repository->findById($citizen->getDelegate());
            foreach ($delegates as $entity) {
                $delegateName = $entity->getName() . " " . $entity->getSurname();
            }
        }

        $form = $instance->createForm(CitizenType::class, $citizen,
                array('tickettypes' => $ticketTypes,
                    'meals' => $meals,
                    'rooms' => $rooms,
                    'buses' => $buses,
                    'branches' => $branches,
                    'relationships' => $relationships));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $instance->getUser();
            if ($citizen->getUid() == 0) {
                $citizen->setUid($user->getId());
            }

            $now = new \DateTime("now");

            // MEALS INIT
            $data = $form['meals']->getData();
            if (!empty($data)) {
                $citizen->registerMeals($data, $meals, $eventId,
                        $user->getId(), $cid, $now);
            }
            // MEALS END


            // ROOM INIT
            $entity = $form['rooms']->getData();
            if (!empty($entity)) {
                $citizen->registerRoom($entity->getId(), $rooms, $user->getId(),
                        $cid, $now);
            }
            // ROOM END

            // TRANSPORT INIT
            $databus = $form['buses']->getData();
            if (!empty($databus)) {
                $citizen->registerBus($databus, $buses, $eventId,
                        $user->getId(), $cid, $now);
            }
            // TRANSPORT END

            // TICKETS INIT
            $datatt = $form['tickettypes']->getData();
            if (!empty($datatt)) {
                $citizen->registerTicketEvent($ticketTypes, $datatt->getId(),
                        $eventId, $user->getId(), $cid, $now);
            }
            // TICKETS END

            $entityManager = $instance->getDoctrine()->getManager();
            if ($persistCitizen) {
                $entityManager->persist($citizen);
            } else {
                $task->setUid($user->getId());
                $task->setPayed(0);
                $task->setOrdered(0);
                $task->setOrderedDate(new \DateTime());
                $task->setAmount(0);
                $entityManager->persist($task);
            }

            $entityManager->flush();

            return $instance->redirectToRoute('task_show', ['id' => $citizen->getTask()->getId()]);
        }

        $mids = array();
        foreach ($citizen->getTicketsrestaurant() as $ticket) {
            array_push($mids, $ticket->getMid());
        }

        $rids = array();
        foreach ($citizen->getTicketsroom() as $ticket) {
            array_push($rids, $ticket->getRid());
        }

        $tids = array();
        if (!empty($citizen->getTicketsevent())) {
            array_push($tids, $citizen->getTicketsevent()->getTtid());
        }

        $bids = array();
        foreach ($citizen->getTicketsbus() as $ticketbus) {
            array_push($bids, $ticketbus->getBid());
        }

        if (!$persistCitizen) {
            return $instance->render('admin/task/new.html.twig', array(
                'ids' => [2, 6],
                'c' => $mids,
                'm' => $meals,
                'h' => $rids,
                'd' => $delegateName,
                't' => $tids,
                'b' => $bids,
                'mealscount' => $jMealsCount,
                'roomscount' => $jRoomsCount,
                'roomMeals' => $jRoomMeals,
                'e' => $task->getEvent(),
                'form' => $form->createView(),
            ));
        }

        return $instance->render('admin/task/edit.html.twig', [
            'c' => $mids,
            'm' => $meals,
            'h' => $rids,
            'd' => $delegateName,
            't' => $tids,
            'b' => $bids,
            'mealscount' => $jMealsCount,
            'roomscount' => $jRoomsCount,
            'roomMeals' => $jRoomMeals,
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     * @Route("/citizen/{id}/edit", requirements={"id": "\d+"}, name="citizen_edit", methods={"POST", "GET"})
     */
    public function editAction(Citizen $citizen, Request $request) {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        return CitizenController::edit($this, $this->doctrine, $citizen->getTask(), $citizen, $request, $citizen->getId(), 'citizen.update_successfully', true);
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     *
     * @Route("/citizen/{id}/add", requirements={"id": "\d+"}, name="citizen_add", methods={"POST", "GET"})
     */
    public function addAction(Task $task, Request $request) {
        //$this->denyAccessUnlessGranted('edit', $post, 'Posts can only be edited by their authors.');
//@Security("is_granted('ROLE_ADMIN')")
        $citizen = new Citizen();
        $citizen->setTask($task);
        $citizen->setDelegate(0);
        $citizen->setDeleted(0);
        $citizen->setValid(0);
        $citizen->setEid($task->getEvent()->getId());

        return $this->edit($this, $task, $citizen, $request, 0, 'citizen.created_successfully', true);
    }

    /**
     * @Route("/citizen/handleSearch/{_query?}", name="citizen_handle_search", methods={"POST", "GET"})
     */
    public function handleSearchRequest(Request $request, $_query)
    {
        $em = $this->doctrine->getManager();
        if ($_query) {
            if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                $data = $em->getRepository(Citizen::class)->findByName($_query);
            } else {
                $userid = $this->getUser()->getId();
                $data = $em->getRepository(Citizen::class)->findByNameAndUser($_query, $userid);
            }

        } else {
            $data = $em->getRepository(Citizen::class)->findAll();
        }
        $result = array();
        foreach ($data as $citizen) {
            $url = $this->generateUrl(
                                'task_show',
                                array('id' => $citizen->getTask()->getId()));

            $result[] = array('id' => $citizen->getId(),
                            'name' => $citizen->getName(),
                            'surname' => $citizen->getSurname(),
                            'cityb' => $citizen->getCityBirth(),
                            'dateb' => $citizen->getBirthDate(),
                            'url' => $url);
        }
        $data =  json_encode($result);
        return new JsonResponse($data, 200, [], true);
    }

    /**
    * @Route("/city/{id?}", name="city_page", methods={"GET"})
    */
    public function citySingle(Request $request, $id)
    {
        $em = $this->doctrine->getManager();
        $city = null;

        if ($id) {
            $city = $em->getRepository(Cities::class)->findOneBy(['id' => $id]);
        }
        return $this->render('home/city.html.twig', [
            'city'  =>      $city
        ]);
    }

    /**
     * Displays a form to save CitizenPayment entity.
     *
     *
     * @Route("/citizen/payment", name="admin_citizen_new_payment", methods={"POST", "GET"})
     */
    public function newPaymentAction(Request $request) {


        $payment = new CitizenPayment();

        $form = $this->createForm(CitizenPaymentType::class, $payment);
        $form->handleRequest($request);
        $result = array();
        $result['status'] = 'Waiting';

        if ($form->isSubmitted() && $form->isValid()) {

            if (empty($payment->getCode())) {
                $this->addFlash('error', 'payment.added_error');
                $result['status'] = 'ERROR';

            } else {

                $repositoryTask = $this->doctrine->getRepository(Task::class);
                $task = $repositoryTask->findOneByCode($payment->getCode());

                if ($task) {

                    $user = $this->getUser();

                    $payment->setTid($task->getId());

                    $payment->setUid($user->getId());
                    $payment->setUpdateDate(new \DateTime());
                    $payment->setDeleted(false);

                    $entityManager = $this->doctrine->getManager();
                    $entityManager->persist($payment);
                    $entityManager->flush();

                    $payments = $this->doctrine->getRepository(CitizenPayment::class)->findAllByTask($payment->getTid());
                    $total = 0.0;
                    foreach ($payments as $p) {
                        $total += $p->getValue();
                    }
                    $diff = $total - $task->getAmount();

                    $this->addFlash('success', 'payment.deleted_successfully');

                    $result['status'] = 'OK';
                    $result['total'] = $total;
                    $result['diff'] = $diff;
                    $result['pid'] = $payment->getId();
                    $result['del'] = $this->generateUrl('admin_citizen_delete_payment', array('id' =>$payment->getId()));

                    $this->addFlash('success', 'payment.added_successfully');

                } else {
                    $result['status'] = 'ERROR';
                }
            }
        }

        $data =  json_encode($result);
        return new JsonResponse($data, 200, [], true);
    }

    /**
     * Displays a form to save CitizenPayment entity.
     *
     *
     * @Route("/payment/{id}/delete", requirements={"id": "\d+"}, name="admin_citizen_delete_payment", methods={"POST", "GET"})
     */
    public function deletePaymentAction(CitizenPayment $payment, Request $request) {

        $user = $this->getUser();
        $payment->setDuid($user->getId());
        $payment->setDeleteDate(new \DateTime());
        $payment->setDeleted(true);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($payment);
        $entityManager->flush();

        $repositoryTask = $this->doctrine->getRepository(Task::class);
        $task = $repositoryTask->findOneId($payment->getTid());

        $payments = $this->doctrine->getRepository(CitizenPayment::class)->findAllByTask($payment->getTid());
        $total = 0.0;
        foreach ($payments as $p) {
            $total += $p->getValue();
        }
        $diff = $total - $task->getAmount();

        $this->addFlash('success', 'payment.deleted_successfully');

        $result['status'] = 'OK';
        $result['total'] = $total;
        $result['diff'] = $diff;


        $data =  json_encode($result);
        return new JsonResponse($data, 200, [], true);
    }


    /**
     * Displays a form to save CitizenPayment entity.
     *
     *
     * @Route("/citizen/check/{citizen}/{type}", requirements={"citizen": "\d+", "type": "\d+",}, name="admin_citizen_check", methods={"POST", "GET"})
     */
    public function checkAction(Citizen $citizen, $type, Request $request) {

        $checkin = CheckIn::create($citizen, $type, $this->getUser()->getId());
        $citizen->addCheckin($checkin);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($citizen);
        $entityManager->flush();
        $result = array();
        $result['status'] = 'OK';
        $result['check']['type'] = $type;
        $result['check']['checkDate'] = $checkin->getCheckDate();
        $t = $checkin->getType() ? 0 : 1;
        $result['check']['url'] = $this->generateUrl('admin_citizen_check', array('citizen' => $citizen->getId(), 'type' => $t));
        $data =  json_encode($result);
        return new JsonResponse($data, 200, [], true);
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/citizen/delete/{id}/{d}", requirements={"id": "\d+", "d": "\d+"}, name="citizen_delete", methods={"POST", "GET"})
     */
    public function deleteAction(Citizen $citizen, $d, Request $request) {
        $result = array();

        if ($d == 0 || $d == 1) {
            $citizen->setDeleted($d);
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($citizen);
            $entityManager->flush();
            $result['status'] = 'OK';
            $result['d'] = (($d+1)%2);
            $result['url'] = $this->generateUrl(
                                'citizen_delete',
                                array('id' => $citizen->getId(), 'd' => (($d+1)%2) ));
        } else {
            $result['status'] = 'error';
        }

        $data =  json_encode($result);
        return new JsonResponse($data, 200, [], true);
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/citizen/deleteticketduplicated/{id}", requirements={"id": "\d+", "d": "\d+"}, name="citizen_delete_ticket_duplicated", methods={"POST", "GET"})
     */
    public function deleteTicketDuplicatedAction($id, Request $request) {

        $em = $this->doctrine->getManager();

        $data = $em->getRepository(Citizen::class)->findTicketDuplicated($id);

        $result[] = array();

        foreach ($data as $c) {


            $cid = $c['cid'];
            $tid = $c['tid'];

            if (!isset($result[$cid])) {
                $result[$cid] = $tid;
            } else {
                $ticket = $em->getRepository(TicketCostCitizen::class)->find($tid);
                $ticket->setCitizen(null);
                $em->persist($ticket);
                $em->flush();
            }

        }

        return new JsonResponse(json_encode($result), 200, [], true);
    }





    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/admin/hotelreal/allocationmap/{id}", requirements={"id": "\d+"}, name="admin_hotel_allocation_map", methods={"POST", "GET"})
     */
    public function allocationMapAction($id) {

        $url = $this->generateUrl(
                        'task_handle_search_allocation_map',
                        array('event' => $id));

        return $this->render('admin/hotelreal/allocationmap.html.twig', ['event' => $id,
            'url' => $url]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/admin/citizen/searchckeckins/{event}", requirements={"event": "\d+"}, name="task_handle_search_checkins_map", methods={"POST", "GET"})
     */
    public function searchCheckinsMapRequest($event)
    {
        $data = $this->doctrine->getRepository(CheckIn::class)->findCheckins($event);

        $result[] = array();

        foreach ($data as $map) {

            $taskID = $map['task'];
            if (!isset($result['tasks'][$taskID])) {
                $url = $this->generateUrl('task_show',array('id' => $taskID));
                $result['tasks'][$taskID] = array();
                $result['tasks'][$taskID]['url'] = $url;
                $result['tasks'][$taskID]['citizens'] = array();
            }

            if (!isset($result['tasks'][$taskID]['citizens'][$map['id']])) {

                $birthDate = \DateTime::createFromFormat('Y-m-d H:i:s', $map['birth_date']);
                $age = -1;
                if ($birthDate) {
                    $age = $birthDate->diff(new \DateTime('now'))->y;
                }

                $result['tasks'][$taskID]['citizens'][$map['id']] = array('id' => $map['id'],
                            'name' => $map['name'],
                            'surname' => $map['surname'],
                            'age' => $age,
                            'type' => $map['type'],
                        );
            }

        }


        unset($result[0]);
        //var_dump($result); die();
        return new JsonResponse(json_encode($result), 200, [], true);
    }
}
