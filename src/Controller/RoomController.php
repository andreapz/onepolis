<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Entity\Room;
use App\Entity\RoomCost;
use App\Form\Type\RoomType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoomController extends AbstractController {

    /**
     * @Route("/room", name="room_index", methods={"GET"})
     * @Cache(smaxage="10")
     */
    public function indexAction() {
        $rooms = $this->doctrine->getRepository(Room::class)->findAll();
        // Every template name also has two extensions that specify the format and
        // engine for that template.
        // See https://symfony.com/doc/current/templating.html#template-suffix
        return $this->render('room/index.html.twig', ['rooms' => $rooms]);
    }

    /**
     * Finds and displays a Room entity.
     *
     * @Route("/admin/room/{id}", requirements={"id": "\d+"}, name="room_show", methods={"GET"})
     */
    public function showAction(Room $room) {
        // This security check can also be performed
        // using an annotation: @Security("is_granted('show', post)")
        //$this->denyAccessUnlessGranted('show', $post, 'Posts can only be shown to their authors.');
        
        return $this->render('admin/room/show.html.twig', [
                    'room' => $room,
                    'hotel_id' => $room->getHotel()->getId(),
                    'hotel_name' => $room->getHotel()->getName(),
        ]);
    }

    /**
     * Creates a new Post entity.
     *
     * @Route("/admin/hotel/{id}/room/new", name="admin_room_new", methods={"POST", "GET"})
     *
     * 
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function newAction(Hotel $hotel, Request $request) {
        $room = new Room();
        $room->setHotel($hotel);
        
        //$post->setAuthor($this->getUser());
//* @Security("is_granted('ROLE_ADMIN')")
        // See http://symfony.com/doc/current/book/forms.html#submitting-forms-with-multiple-buttons
        $form = $this->createForm(RoomType::class, $room);
        // ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See http://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($this->get('slugger')->slugify($post->getTitle()));

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($room);
            $entityManager->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See http://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'room.created_successfully');

            /* if ($form->get('saveAndCreateNew')->isClicked()) {
              return $this->redirectToRoute('admin_hotels');
              } */

            return $this->redirectToRoute('admin_rooms');
        }

        return $this->render('admin/room/new.html.twig', [
                    'post' => $room,
                    'form' => $form->createView(),
        ]);
    }
    
    /**
     * Creates a new Post entity.
     *
     * @Route("/admin/room/{id}/add", requirements={"id": "\d+"}, name="admin_room_add", methods={"POST", "GET"})
     *
     * 
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function addHotelAction(Hotel $hotel, Request $request) {
        $room = new Room();
        $room->setHotel($hotel);
        
        //$post->setAuthor($this->getUser());
//* @Security("is_granted('ROLE_ADMIN')")
        // See http://symfony.com/doc/current/book/forms.html#submitting-forms-with-multiple-buttons
        $form = $this->createForm(RoomType::class, $room);
        // ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See http://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($this->get('slugger')->slugify($post->getTitle()));
            
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($room);
            $entityManager->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See http://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'room.created_successfully');

            /* if ($form->get('saveAndCreateNew')->isClicked()) {
              return $this->redirectToRoute('admin_hotels');
              } */

            return $this->redirectToRoute('admin_rooms');
        }

        return $this->render('admin/room/new.html.twig', [
                    'post' => $room,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Room entity.
     *
     * 
     * @Route("/admin/room/{id}/edit", requirements={"id": "\d+"}, name="admin_room_edit", methods={"POST", "GET"})
     */
    public function editAction(Room $room, Request $request) {
        //$this->denyAccessUnlessGranted('edit', $post, 'Posts can only be edited by their authors.');
        //@Security("is_granted('ROLE_ADMIN')")
        $entityManager = $this->doctrine->getManager();
        $originalTickets = new ArrayCollection();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($room->getTickets() as $ticket) {
            $originalTickets->add($ticket);
        }

        $form = $this->createForm(RoomType::class, $room);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($room->getTickets() as $ticket) {
                $ticket->setRoom($room);
            }
            
            foreach ($originalTickets as $ticket) {
                if (false === $room->getTickets()->contains($ticket)) {
                    $ticket->setRoom(null);
                    $entityManager->remove($ticket);
                    $entityManager->persist($ticket);
                }
            }
            
            $entityManager->flush();

            $this->addFlash('success', 'post.updated_successfully');

            return $this->redirectToRoute('admin_room_edit', ['id' => $room->getId()]);
        }

        return $this->render('admin/room/edit.html.twig', [
                    'room' => $room,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/room/create")
     */
    public function createAction() {
        $hotel = new Hotel();
        $hotel->setTitle('Mariapoli2018');

        $em = $this->doctrine->getManager();

        // tells Doctrine you want to (hotelually) save the Product (no queries yet)
        $em->persist($hotel);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return new Response('Saved new product with id ' . $hotel->getId());
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/admin/rooms", name="admin_rooms", methods={"GET"})
     */
    public function roomsAction() {
        $rooms = $this->doctrine
                ->getRepository('App:Room')
                ->findAll();

        return $this->render('admin/room/rooms.html.twig', [
                    'rooms' => $rooms,
        ]);
    }

    /**
     * @Route("/admin/room/update/{roomId}")
     */
    public function updateAction($roomId) {
        $em = $this->doctrine->getManager();
        $hotel = $em->getRepository('App:Hotel')->find($hotelId);

        if (!$hotel) {
            throw $this->createNotFoundException(
                    'No product found for id ' . $hotelId
            );
        }

        $hotel->setTitle('Mariapoli2019');
        $em->flush();

        return $this->redirectToRoute('showAll');
    }

}