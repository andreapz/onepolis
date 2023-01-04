<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Entity\Room;
use App\Entity\RoomCost;
use App\Form\Type\RoomCostType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoomCostController extends AbstractController {
    
    public function __construct(private ManagerRegistry $doctrine) {}

    /**
     * Finds and displays a Room entity.
     *
     * @Route("/admin/roomticket/{id}", requirements={"id": "\d+"}, name="room_ticket_show", methods={"GET"})
     */
    public function showAction(RoomCost $roomcost) {
        // This security check can also be performed
        // using an annotation: @Security("is_granted('show', post)")
        //$this->denyAccessUnlessGranted('show', $post, 'Posts can only be shown to their authors.');
        
        return $this->render('admin/room/ticket/show.html.twig', [
                    'roomcost' => $roomcost,
        ]);
    }

    
    
    /**
     * Displays a form to edit an existing Room entity.
     *
     * 
     * @Route("/admin/room/ticket/{id}/edit", requirements={"id": "\d+"}, name="room_ticket_edit", methods={"POST", "GET"})
     */
    public function editAction(RoomCost $roomcost, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(RoomCostType::class, $roomcost);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

           $entityManager = $this->doctrine->getManager();
            $entityManager->persist($roomcost);
            $entityManager->flush();

            $this->addFlash('success', 'post.updated_successfully');

            return $this->redirectToRoute('room_ticket_show', ['id' => $roomcost->getId()]);
        }

        return $this->render('admin/room/ticket/edit.html.twig', [
                    'roomcost' => $roomcost,
                    'form' => $form->createView(),
        ]);
    }
    
    
    

    /**
     * Creates a new RestaurantCost entity.
     *
     * @Route("/admin/room/{id}/ticket/new", name="admin_room_cost_new", methods={"POST", "GET"})
     *
     * 
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function newAction(Room $room, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $roomCost = new RoomCost();
        $roomCost->setRoom($room);
        $roomCost->seteid($room->getEid());
        
        //$post->setAuthor($this->getUser());
        // See http://symfony.com/doc/current/book/forms.html#submitting-forms-with-multiple-buttons
        $form = $this->createForm(RoomCostType::class, $roomCost);
        // ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See http://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($this->get('slugger')->slugify($post->getTitle()));

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($roomCost);
            $entityManager->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See http://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'roomcost.created_successfully');

            /* if ($form->get('saveAndCreateNew')->isClicked()) {
              return $this->redirectToRoute('admin_events');
              } */

            return $this->redirectToRoute('room_show', ['id' => $room->getId()]);
        }

        return $this->render('admin/room/ticket/edit.html.twig', [
                    'roomcost' => $roomCost,
                    'form' => $form->createView(),
        ]);
    }

     /**
    * Delete a new Post entity.
    *
    * @Route("/admin/room/ticket/delete/{id}", requirements={"id": "\d+"}, name="admin_room_cost_delete", methods={"POST", "GET"})
    *
    * 
    * NOTE: the Method annotation is optional, but it's a recommended practice
    * to constraint the HTTP methods each controller responds to (by default
    * it responds to all methods).
    */
    public function deleteAction(RoomCost $roomcost) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $roomId = $roomcost->getRoom()->getId();
        $roomcost->setRoom(null);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($roomcost);
        $entityManager->flush();
        return $this->redirectToRoute('room_show', ['id' => $roomId]);
    }  
}
