<?php

namespace App\Controller;

use App\Entity\HotelReal;
use App\Entity\ExtraCost;
use App\Form\Type\ExtraCostType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ExtraCostController extends AbstractController {
    
    public function __construct(private ManagerRegistry $doctrine) {}
    
    /**
     * @Route("/admin/extracost/list/{id}", requirements={"id": "\d+"}, name="extracost_index", methods={"GET"})
     * @Cache(smaxage="10")
     */
    public function indexAction($id) {
        $rooms = $this->doctrine->getRepository(ExtraCost::class)->findAll();
        // Every template name also has two extensions that specify the format and
        // engine for that template.
        // See https://symfony.com/doc/current/templating.html#template-suffix
        return $this->render('extracost/index.html.twig', ['rooms' => $rooms]);
    }
    
    /**
    * Creates a new Post entity.
    *
    * @Route("/admin/extracost/new/{id}", requirements={"id": "\d+"}, name="admin_extracost_new", methods={"POST", "GET"})
    *
    * 
    * NOTE: the Method annotation is optional, but it's a recommended practice
    * to constraint the HTTP methods each controller responds to (by default
    * it responds to all methods).
    */
    public function newAction(HotelReal $hotel, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $extra = new ExtraCost();
        $extra->setHotel($hotel);

        return $this->form($extra, $request);
    }    

   /**
    * Creates a new Post entity.
    *
    * @Route("/admin/extracost/{id}", requirements={"id": "\d+"}, name="admin_extracost_show", methods={"POST", "GET"})
    *
    * 
    * NOTE: the Method annotation is optional, but it's a recommended practice
    * to constraint the HTTP methods each controller responds to (by default
    * it responds to all methods).
    */
    public function showAction(ExtraCost $extra, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/extracost/show.html.twig', ['extra' => $extra]);
    }   

    /**
     * Creates a new Post entity.
     *
     * @Route("/admin/extracost/edit/{id}", requirements={"id": "\d+"}, name="admin_extracost_edit", methods={"POST", "GET"})
     *
     * 
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function editAction(ExtraCost $extra, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->form($extra, $request);

    }    

    public function form(ExtraCost $extra, Request $request) {
        $form = $this->createForm(ExtraCostType::class, $extra);
        $form->handleRequest($request);
        $result = array();
        $result['status'] = 'Waiting'; 
        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See http://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($this->get('slugger')->slugify($post->getTitle()));

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($extra);
            $entityManager->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See http://symfony.com/doc/current/book/controller.html#flash-messages
            //$this->addFlash('success', 'room.created_successfully');

            /* if ($form->get('saveAndCreateNew')->isClicked()) {
              return $this->redirectToRoute('admin_events');
              } */

            //return $this->redirectToRoute('admin_room_real_index', ['id' => $room->getEvent()]);
            $result['status'] = 'OK';
            $result['room']['id'] = $extra->getId();
            $result['html'] = $this->renderView('admin/extracost/_row.html.twig', array('extracost' => $extra));
        }

       $data =  json_encode($result);
       return new JsonResponse($data, 200, [], true);
    }
        
    /**
    * Delete a new Post entity.
    *
    * @Route("/admin/extracost/delete/{id}", requirements={"id": "\d+"}, name="admin_extracost_delete", methods={"POST", "GET"})
    *
    */
    public function deleteAction(ExtraCost $room) {
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