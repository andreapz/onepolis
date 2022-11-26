<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\RestaurantMeal;
use App\Repository\RestaurantMealRepository;
use App\Form\Type\RestaurantMealType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantMealController extends AbstractController {


    /**
     * Displays a form to edit an existing Task entity.
     *
     * 
     * @Route("/admin/restaurant/meal/{id}/edit", requirements={"id": "\d+"}, name="restaurant_meal_edit", methods={"POST", "GET"})
     */
    public function editAction(RestaurantMeal $restaurantMeal, Request $request) {
        
        $form = $this->createForm(RestaurantMealType::class, $restaurantMeal);
        $form->handleRequest($request);

        $originalTickets = new ArrayCollection();
        foreach ($restaurantMeal->getTickets() as $ticket) {
            $originalTickets->add($ticket);
        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($this->get('slugger')->slugify($post->getTitle()));
            foreach ($restaurantMeal->getTickets() as $ticket) {
                $ticket->setMeal($restaurantMeal);
            }
            
            $entityManager = $this->getDoctrine()->getManager();
            
            foreach ($originalTickets as $ticket) {
                if (false === $restaurantMeal->getTickets()->contains($ticket)) {
                    $ticket->setMeal(null);
                    $entityManager->remove($ticket);
                    $entityManager->persist($ticket);
                }
            }
            
            $entityManager->persist($restaurantMeal);
            $entityManager->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See http://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'restaurantmeal.updated_successfully');

            /* if ($form->get('saveAndCreateNew')->isClicked()) {
              return $this->redirectToRoute('admin_events');
              } */

            return $this->redirectToRoute('restaurant_meal_show', ['id' => $restaurantMeal->getId()]);
        }

        return $this->render('admin/restaurant/meal/edit.html.twig', [
            'form' => $form->createView(),
            'meal' => $restaurantMeal,
        ]);     
    }
    
    /**
     * Displays a form to edit an existing Task entity.
     *
     * 
     * @Route("/admin/restaurant/meal/{id}/", requirements={"id": "\d+"}, name="restaurant_meal_show", methods={"POST", "GET"})
     */
    public function showAction(RestaurantMeal $restaurantMeal) {
        return $this->render('admin/restaurant/meal/show.html.twig', [
                    'restaurantMeal' => $restaurantMeal
        ]);
    }
   
    
    /**
     * Creates a new RestaurantMeal entity.
     *
     * @Route("/admin/restaurant/{id}/meal/new", name="admin_restaurant_meal_new", methods={"POST", "GET"})
     *
     * 
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function newAction(Restaurant $restaurant, Request $request) {
        $restaurantMeal = new RestaurantMeal();
        $restaurantMeal->setRestaurant($restaurant);
        
        //$post->setAuthor($this->getUser());
//* @Security("is_granted('ROLE_ADMIN')")
        // See http://symfony.com/doc/current/book/forms.html#submitting-forms-with-multiple-buttons
        $form = $this->createForm(RestaurantMealType::class, $restaurantMeal);
        // ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See http://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($this->get('slugger')->slugify($post->getTitle()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($restaurantMeal);
            $entityManager->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See http://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'restaurant.created_successfully');

            /* if ($form->get('saveAndCreateNew')->isClicked()) {
              return $this->redirectToRoute('admin_events');
              } */

            return $this->redirectToRoute('restaurant_show', ['id' => $restaurant->getId()]);
        }

        return $this->render('admin/restaurant/meal/edit.html.twig', [
                    'meal' => $restaurantMeal,
                    'form' => $form->createView(),
        ]);
    }

}
