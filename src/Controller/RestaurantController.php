<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Restaurant;
use App\Entity\RestaurantCost;
use App\Form\Type\RestaurantType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantController extends AbstractController {
    public function __construct(private ManagerRegistry $doctrine) {}
    
    /**
     * @Route("/restaurant", name="restaurant_index", methods={"POST", "GET"})
     * @Cache(smaxage="10")
     */
    public function indexAction() {
        $restaurants = $this->doctrine->getRepository(Restaurant::class)->findAll();
        // Every template name also has two extensions that specify the format and
        // engine for that template.
        // See https://symfony.com/doc/current/templating.html#template-suffix
        return $this->render('restaurant/index.html.twig', ['restaurants' => $restaurants]);
    }
    
    
    /**
     * Finds and displays a Restaurant entity.
     *
     * @Route("/restaurant/{id}", requirements={"id": "\d+"}, name="restaurant_show", methods={"GET"})
     */
    public function showAction(Restaurant $restaurant) {
        // This security check can also be performed
        // using an annotation: @Security("is_granted('show', post)")
        //$this->denyAccessUnlessGranted('show', $post, 'Posts can only be shown to their authors.');
        
        return $this->render('admin/restaurant/show.html.twig', [
                    'restaurant' => $restaurant,
                    'event_id' => $restaurant->getEvent()->getId(),
                    'event_title' => $restaurant->getEvent()->getTitle(),
        ]);
    }

    /**
     * Creates a new Post entity.
     *
     * @Route("/admin/restaurant/new", name="admin_restaurant_new", methods={"POST", "GET"})
     *
     * 
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function newAction(Request $request) {
        $restaurant = new Restaurant();
        
        //$post->setAuthor($this->getUser());
//* @Security("is_granted('ROLE_ADMIN')")
        // See http://symfony.com/doc/current/book/forms.html#submitting-forms-with-multiple-buttons
        $form = $this->createForm(RestaurantType::class, $restaurant);
        // ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See http://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($this->get('slugger')->slugify($post->getTitle()));

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($restaurant);
            $entityManager->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See http://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'restaurant.created_successfully');

            /* if ($form->get('saveAndCreateNew')->isClicked()) {
              return $this->redirectToRoute('admin_events');
              } */

            return $this->redirectToRoute('admin_restaurants');
        }

        return $this->render('admin/restaurant/new.html.twig', [
                    'post' => $restaurant,
                    'form' => $form->createView(),
        ]);
    }
    
    /**
     * Creates a new Post entity.
     *
     * @Route("/admin/restaurant/{id}/add", requirements={"id": "\d+"}, name="admin_restaurant_add", methods={"POST", "GET"})
     *
     * 
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function addEventAction(Event $event, Request $request) {
        $restaurant = new Restaurant();
        $restaurant->setEvent($event);
        
        //$post->setAuthor($this->getUser());
//* @Security("is_granted('ROLE_ADMIN')")
        // See http://symfony.com/doc/current/book/forms.html#submitting-forms-with-multiple-buttons
        $form = $this->createForm(RestaurantType::class, $restaurant);
        // ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See http://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($this->get('slugger')->slugify($post->getTitle()));
            
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($restaurant);
            $entityManager->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See http://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'restaurant.created_successfully');

            /* if ($form->get('saveAndCreateNew')->isClicked()) {
              return $this->redirectToRoute('admin_events');
              } */

            return $this->redirectToRoute('admin_restaurants');
        }

        return $this->render('admin/restaurant/new.html.twig', [
                    'post' => $restaurant,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Restaurant entity.
     *
     * 
     * @Route("/admin/restaurant/{id}/edit", requirements={"id": "\d+"}, name="admin_restaurant_edit", methods={"POST", "GET"})
     */
    public function editAction(Restaurant $restaurant, Request $request) {
        //$this->denyAccessUnlessGranted('edit', $post, 'Posts can only be edited by their authors.');
        //@Security("is_granted('ROLE_ADMIN')")
        $entityManager = $this->doctrine->getManager();
        $originalMeals = new ArrayCollection();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($restaurant->getMeals() as $meal) {
            $originalMeals->add($meal);
        }

        $form = $this->createForm(RestaurantType::class, $restaurant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($restaurant->getMeals() as $meal) {
                $meal->setRestaurant($restaurant);
            }
            
            foreach ($originalMeals as $meal) {
                if (false === $restaurant->getMeals()->contains($meal)) {
                    $meal->setRestaurant(null);
                    $entityManager->remove($meal);
                    $entityManager->persist($meal);
                }
            }
            
            $entityManager->flush();

            $this->addFlash('success', 'post.updated_successfully');

            return $this->redirectToRoute('admin_restaurant_edit', ['id' => $restaurant->getId()]);
        }

        return $this->render('admin/restaurant/edit.html.twig', [
                    'restaurant' => $restaurant,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/restaurant/create")
     */
    public function createAction() {
        $event = new Event();
        $event->setTitle('Mariapoli2018');

        $em = $this->doctrine->getManager();

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($event);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return new Response('Saved new product with id ' . $event->getId());
    }

    /**
     * @Route("/admin/restaurants", name="admin_restaurants", methods={"GET"})
     */
    public function restaurantsAction() {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $restaurants = $this->doctrine
                ->getRepository('App:Restaurant')
                ->findAll();

        return $this->render('admin/restaurant/restaurants.html.twig', [
                    'restaurants' => $restaurants,
        ]);
    }

    /**
     * @Route("/admin/restaurant/update/{restaurantId}")
     */
    public function updateAction($restaurantId) {
        $em = $this->doctrine->getManager();
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
