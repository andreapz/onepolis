<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Entity\Room;
use App\Entity\RoomBase;
use App\Entity\RoomCost;
use App\Form\Type\RoomType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoomBaseController extends AbstractController {

    public function __construct(private ManagerRegistry $doctrine) {}

    /**
     * @Route("/roombase", name="roombase_index", methods={"GET"})
     * @Cache(smaxage="10")
     */
    public function indexAction() {
        $rooms = $this->doctrine->getRepository(RoomBase::class)->findAll();
        // Every template name also has two extensions that specify the format and
        // engine for that template.
        // See https://symfony.com/doc/current/templating.html#template-suffix
        return $this->render('roombase/index.html.twig', ['rooms' => $rooms]);
    }

    /**
     * Finds and displays a Roombase entity.
     *
     * @Route("/admin/roombase/{id}", requirements={"id": "\d+"}, name="roombase_show", methods={"GET"})
     */
    public function showAction(RoomBase $roombase) {
        // This security check can also be performed
        // using an annotation: @Security("is_granted('show', post)")
        //$this->denyAccessUnlessGranted('show', $post, 'Posts can only be shown to their authors.');
        
        return $this->render('admin/roombase/show.html.twig', [
                    'roombase' => $roombase,
                    'hotel_id' => $roombase->getHotel()->getId(),
                    'hotel_name' => $roombase->getHotel()->getName(),
        ]);
    }

    /**
     * Creates a new Post entity.
     *
     * @Route("/admin/hotel/{id}/roombase/new", name="admin_roombase_new", methods={"POST", "GET"})
     *
     * 
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function newAction(Hotel $hotel, Request $request) {
        $room = new RoomBase();
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

        return $this->render('admin/roombase/new.html.twig', [
                    'post' => $room,
                    'form' => $form->createView(),
        ]);
    }
    
    /**
     * Creates a new Post entity.
     *
     * @Route("/admin/roombase/{id}/add", requirements={"id": "\d+"}, name="admin_roombase_add", methods={"POST", "GET"})
     *
     * 
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function addHotelAction(Hotel $hotel, Request $request) {
        $room = new RoomBase();
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

        return $this->render('admin/roombase/new.html.twig', [
                    'post' => $room,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Room entity.
     *
     * 
     * @Route("/admin/roombase/{id}/edit", requirements={"id": "\d+"}, name="admin_roombase_edit", methods={"POST", "GET"})
     */
    public function editAction(RoomBase $room, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
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

            return $this->redirectToRoute('admin_roombase_edit', ['id' => $room->getId()]);
        }

        return $this->render('admin/roombase/edit.html.twig', [
                    'room' => $room,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/roombase/create")
     */
    public function createAction() {
        $hotel = new Hotel();
        $hotel->setTitle('Mariapoli2023');

        $em = $this->doctrine->getManager();

        // tells Doctrine you want to (hotelually) save the Product (no queries yet)
        $em->persist($hotel);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return new Response('Saved new product with id ' . $hotel->getId());
    }

    /**
     * @Route("/admin/roomsbase", name="admin_roomsbase", methods={"GET"})
     */
    public function roomsAction() {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $rooms = $this->doctrine
                ->getRepository('App:RoomBase')
                ->findAll();

        return $this->render('admin/roombase/rooms.html.twig', [
                    'rooms' => $rooms,
        ]);
    }

    /**
     * @Route("/admin/roombase/update/{roomId}")
     */
    public function updateAction($roomId) {
        $em = $this->doctrine->getManager();
        $hotel = $em->getRepository('App:Hotel')->find($hotelId);

        if (!$hotel) {
            throw $this->createNotFoundException(
                    'No product found for id ' . $hotelId
            );
        }

        $hotel->setTitle('Mariapoli2023');
        $em->flush();

        return $this->redirectToRoute('showAll');
    }

}
