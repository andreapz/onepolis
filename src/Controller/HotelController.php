<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Hotel;
use App\Entity\Room;
use App\Form\Type\HotelType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HotelController extends AbstractController {

    /**
     * @Route("/hotel", name="hotel_index", methods={"GET"})
     * @Cache(smaxage="10")
     */
    public function indexAction() {
        $hotels = $this->getDoctrine()->getRepository(Hotel::class)->findAll();
        // Every template name also has two extensions that specify the format and
        // engine for that template.
        // See https://symfony.com/doc/current/templating.html#template-suffix
        return $this->render('hotel/index.html.twig', ['hotels' => $hotels]);
    }

    /**
     * Finds and displays a Hotel entity.
     *
     * @Route("/hotel/{id}", requirements={"id": "\d+"}, name="hotel_show", methods={"POST", "GET"})
     */
    public function showAction(Hotel $hotel) {
        // This security check can also be performed
        // using an annotation: @Security("is_granted('show', post)")
        //$this->denyAccessUnlessGranted('show', $post, 'Posts can only be shown to their authors.');
        
        return $this->render('admin/hotel/show.html.twig', [
                    'hotel' => $hotel,
                    'event_id' => $hotel->getEvent()->getId(),
                    'event_title' => $hotel->getEvent()->getTitle(),
        ]);
    }

    /**
     * Creates a new Post entity.
     *
     * @Route("/admin/hotel/new", name="admin_hotel_new", methods={"POST", "GET"})
     *
     * 
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function newAction(Request $request) {
        $hotel = new Hotel();
        
        //$post->setAuthor($this->getUser());
//* @Security("is_granted('ROLE_ADMIN')")
        // See http://symfony.com/doc/current/book/forms.html#submitting-forms-with-multiple-buttons
        $form = $this->createForm(HotelType::class, $hotel);
        // ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See http://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($this->get('slugger')->slugify($post->getTitle()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($hotel);
            $entityManager->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See http://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'hotel.created_successfully');

            /* if ($form->get('saveAndCreateNew')->isClicked()) {
              return $this->redirectToRoute('admin_events');
              } */

            return $this->redirectToRoute('admin_hotels');
        }

        return $this->render('admin/hotel/new.html.twig', [
                    'post' => $hotel,
                    'form' => $form->createView(),
        ]);
    }
    
    /**
     * Creates a new Post entity.
     *
     * @Route("/admin/hotel/{id}/add", requirements={"id": "\d+"}, name="admin_hotel_add", methods={"POST", "GET"})
     *
     * 
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function addEventAction(Event $event, Request $request) {
        $hotel = new Hotel();
        $hotel->setEvent($event);
        
        //$post->setAuthor($this->getUser());
//* @Security("is_granted('ROLE_ADMIN')")
        // See http://symfony.com/doc/current/book/forms.html#submitting-forms-with-multiple-buttons
        $form = $this->createForm(HotelType::class, $hotel);
        // ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See http://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($this->get('slugger')->slugify($post->getTitle()));
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($hotel);
            $entityManager->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See http://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'hotel.created_successfully');

            /* if ($form->get('saveAndCreateNew')->isClicked()) {
              return $this->redirectToRoute('admin_events');
              } */

            return $this->redirectToRoute('admin_hotels');
        }

        return $this->render('admin/hotel/new.html.twig', [
                    'post' => $hotel,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Hotel entity.
     *
     * 
     * @Route("/admin/hotel/{id}/edit", requirements={"id": "\d+"}, name="admin_hotel_edit", methods={"POST", "GET"})
     */
    public function editAction(Hotel $hotel, Request $request) {
        //$this->denyAccessUnlessGranted('edit', $post, 'Posts can only be edited by their authors.');
        //@Security("is_granted('ROLE_ADMIN')")
        $entityManager = $this->getDoctrine()->getManager();
        $originalRooms = new ArrayCollection();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($hotel->getRooms() as $room) {
            $originalRooms->add($room);
        }

        $form = $this->createForm(HotelType::class, $hotel);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($hotel->getRooms() as $room) {
                $room->setHotel($hotel);
            }
            
            foreach ($originalRooms as $room) {
                if (false === $hotel->getRooms()->contains($room)) {
                    $room->setHotel(null);
                    $entityManager->remove($room);
                    $entityManager->persist($room);
                }
            }
            
            $entityManager->flush();

            $this->addFlash('success', 'post.updated_successfully');

            return $this->redirectToRoute('admin_hotel_edit', ['id' => $hotel->getId()]);
        }

        return $this->render('admin/hotel/edit.html.twig', [
                    'hotel' => $hotel,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/hotel/create")
     */
    public function createAction() {
        $event = new Event();
        $event->setTitle('Mariapoli2018');

        $em = $this->getDoctrine()->getManager();

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($event);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return new Response('Saved new product with id ' . $event->getId());
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/admin/hotels", name="admin_hotels", methods={"POST", "GET"})
     */
    public function hotelsAction() {
        $hotels = $this->getDoctrine()
                ->getRepository('App:Hotel')
                ->findAll();

        return $this->render('admin/hotel/hotels.html.twig', [
                    'hotels' => $hotels,
        ]);
    }

    /**
     * @Route("/admin/hotel/update/{hotelId}")
     */
    public function updateAction($hotelId) {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('App:Event')->find($eventId);

        if (!$event) {
            throw $this->createNotFoundException(
                    'No product found for id ' . $eventId
            );
        }

        $event->setTitle('Mariapoli2019');
        $em->flush();

        return $this->redirectToRoute('showAll');
    }

}
