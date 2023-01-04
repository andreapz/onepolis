<?php

namespace App\Controller;

use App\Entity\HotelReal;
use App\Entity\RoomReal;
use App\Form\Type\RoomRealType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RoomRealController extends AbstractController {
    
    /**
     * @Route("/admin/roomreal/list/{id}", requirements={"id": "\d+"}, name="roomreal_index", methods={"GET"})
     * @Cache(smaxage="10")
     */
    public function indexAction($id) {
        $rooms = $this->doctrine->getRepository(RoomReal::class)->findAll();
        return $this->render('roomreal/index.html.twig', ['rooms' => $rooms]);
    }
    
    /**
    * Creates a new Post entity.
    *
    * @Route("/admin/roomreal/new/{id}", requirements={"id": "\d+"}, name="admin_roomreal_new", methods={"POST", "GET"})
    *
    */
    public function newAction(HotelReal $hotel, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $room = new RoomReal();
        $room->setHotel($hotel);

        return $this->form($room, $request);
    }    

   /**
    * Creates a new Post entity.
    *
    * @Route("/admin/roomreal/{id}", requirements={"id": "\d+"}, name="admin_roomreal_show", methods={"POST", "GET"})
    *
    * 
    * NOTE: the Method annotation is optional, but it's a recommended practice
    * to constraint the HTTP methods each controller responds to (by default
    * it responds to all methods).
    */
    public function showAction(RoomReal $room, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/roomreal/show.html.twig', ['room' => $room]);
    }   

    /**
     * Creates a new Post entity.
     *
     * @Route("/admin/roomreal/edit/{id}", requirements={"id": "\d+"}, name="admin_roomreal_edit", methods={"POST", "GET"})
     *
     * 
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function editAction(RoomReal $room, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->form($room, $request);

    }    

    public function form(RoomReal $room, Request $request) {
        
        $rooms = array();
        foreach ($room->getHotel()->getHotel()->getRooms() as $roombase) {
            array_push($rooms, $roombase);    
        }
        
        $form = $this->createForm(RoomRealType::class, $room, 
                array('roomvirtuals' => $rooms));
        $form->handleRequest($request);
        $result = array();
        $result['status'] = 'Waiting'; 

        if ($form->isSubmitted() && $form->isValid()) {    
            $entity = $form['room']->getData();
            
            $room->setRoom($entity);
            
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($room);
            $entityManager->flush();
            $result['status'] = 'OK';
            $result['room']['id'] = $room->getId();
            $result['html'] = $this->renderView('admin/roomreal/_row.html.twig', array('room' => $room, 'show_control' => 1));
        }

        $data =  json_encode($result);
        return new JsonResponse($data, 200, [], true);
    }
    
    /**
    * Delete a new Post entity.
    *
    * @Route("/admin/roomreal/delete/{id}", requirements={"id": "\d+"}, name="admin_roomreal_delete", methods={"POST", "GET"})
    *
    * 
    * NOTE: the Method annotation is optional, but it's a recommended practice
    * to constraint the HTTP methods each controller responds to (by default
    * it responds to all methods).
    */
    public function deleteAction(RoomReal $room) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $result = array();
        $result['status'] = 'ERROR'; 
        $room->setHotel(null);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($room);
        $entityManager->flush();
        $result['status'] = 'OK';
        $result['room']['id'] = $room->getId();
        $data =  json_encode($result);
        return new JsonResponse($data, 200, [], true);
    }  
}