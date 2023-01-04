<?php

namespace App\Controller;

use App\Entity\HotelReal;
use App\Entity\RoomReal;
use App\Entity\RoomRealPrice;
use App\Form\Type\RoomRealPriceType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RoomRealPriceController extends AbstractController {
    
    /**
     * @Route("/admin/roomrealprice/list/{id}", requirements={"id": "\d+"}, name="roomrealprice_index", methods={"GET"})
     * @Cache(smaxage="10")
     */
    public function indexAction($id) {
        $rooms = $this->doctrine->getRepository(RoomRealPrice::class)->findAll();
        // Every template name also has two extensions that specify the format and
        // engine for that template.
        // See https://symfony.com/doc/current/templating.html#template-suffix
        return $this->render('roomrealprice/index.html.twig', ['rooms' => $rooms]);
    }
    
    /**
    * Creates a new Post entity.
    *
    * @Route("/admin/roomrealprice/new/{id}", requirements={"id": "\d+"}, name="admin_roomrealprice_new", methods={"POST", "GET"})
    *
    * 
    * NOTE: the Method annotation is optional, but it's a recommended practice
    * to constraint the HTTP methods each controller responds to (by default
    * it responds to all methods).
    */
   public function newAction(RoomReal $room, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

       $roomprice = new RoomRealPrice();
       $roomprice->setRoom($room);

       return $this->form($roomprice, $request);
   }    

   /**
    * Creates a new Post entity.
    *
    * @Route("/admin/roomrealprice/{id}", requirements={"id": "\d+"}, name="admin_roomrealprice_show", methods={"POST", "GET"})
    *
    * 
    * NOTE: the Method annotation is optional, but it's a recommended practice
    * to constraint the HTTP methods each controller responds to (by default
    * it responds to all methods).
    */
   public function showAction(RoomRealPrice $extra, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/roomrealprice/show.html.twig', ['extra' => $extra]);
    }   

    /**
     * Creates a new Post entity.
     *
     * @Route("/admin/roomrealprice/edit/{id}", requirements={"id": "\d+"}, name="admin_roomrealprice_edit", methods={"POST", "GET"})
     *
     * 
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function editAction(RoomRealPrice $roomprice, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->form($roomprice, $request);

    }    

    public function form(RoomRealPrice $roomprice, Request $request) {
        $form = $this->createForm(RoomRealPriceType::class, $roomprice);
        $form->handleRequest($request);
        $result = array();
        $result['status'] = 'Waiting'; 
        
        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($this->get('slugger')->slugify($post->getTitle()));

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($roomprice);
            $entityManager->flush();

            //return $this->redirectToRoute('admin_room_real_index', ['id' => $room->getEvent()]);
            $result['status'] = 'OK';
            $result['roomprice']['id'] = $roomprice->getId();
            $result['html'] = $this->renderView('admin/roomrealprice/_row.html.twig', array('roomprice' => $roomprice));
        }

       $data =  json_encode($result);
       return new JsonResponse($data, 200, [], true);
    }
        
    /**
    * Delete a new Post entity.
    *
    * @Route("/admin/roomrealprice/delete/{id}", requirements={"id": "\d+"}, name="admin_roomrealprice_delete", methods={"POST", "GET"})
    */
    public function deleteAction(RoomRealPrice $roomprice) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $result = array();
        $result['status'] = 'ERROR'; 
        $roomprice->setRoom(null);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($roomprice);
        $entityManager->flush();
        $result['status'] = 'OK';
        $result['$roomprice']['id'] = $roomprice->getId();
        $data =  json_encode($result);
        return new JsonResponse($data, 200, [], true);
    }  
}