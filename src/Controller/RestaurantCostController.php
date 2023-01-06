<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;


use App\Entity\RestaurantCost;
use App\Entity\RestaurantMeal;
use App\Repository\RestaurantCostRepository;
use App\Form\Type\RestaurantCostType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantCostController extends AbstractController {
    public function __construct(private ManagerRegistry $doctrine) {}

    /**
     * Displays a form to edit an existing Task entity.
     *
     * 
     * @Route("/admin/restaurant/ticket/{id}/edit", requirements={"id": "\d+"}, name="restaurant_cost_edit", methods={"POST", "GET"})
     */
    public function editAction(RestaurantCost $restaurantCost, Request $request) {
        
        $form = $this->createForm(RestaurantCostType::class, $restaurantCost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($this->get('slugger')->slugify($post->getTitle()));
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($restaurantCost);
            $entityManager->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See http://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'restaurantcost.updated_successfully');

            /* if ($form->get('saveAndCreateNew')->isClicked()) {
              return $this->redirectToRoute('admin_events');
              } */

            return $this->redirectToRoute('restaurant_cost_show', ['id' => $restaurantCost->getId()]);
        }

        return $this->render('admin/restaurant/ticket/edit.html.twig', [
            'form' => $form->createView(),
            'restaurantcost' => $restaurantCost,
        ]);     
    }
    
    /**
     * Displays a form to edit an existing Task entity.
     *
     * 
     * @Route("/admin/restaurant/ticket/{id}/", requirements={"id": "\d+"}, name="restaurant_cost_show", methods={"POST", "GET"})
     */
    public function showAction(RestaurantCost $restaurantCost) {
        return $this->render('admin/restaurant/ticket/show.html.twig', [
                    'restaurantCost' => $restaurantCost
        ]);
    }
   

    /**
     * Creates a new RestaurantCost entity.
     *
     * @Route("/admin/restaurant/meal/{id}/ticket/new", name="admin_restaurant_cost_new", methods={"POST", "GET"})
     *
     * 
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function newAction(RestaurantMeal $meal, Request $request) {
        $restaurantCost = new RestaurantCost();
        $restaurantCost->setMeal($meal);
        
        //$post->setAuthor($this->getUser());
//* @Security("is_granted('ROLE_ADMIN')")
        // See http://symfony.com/doc/current/book/forms.html#submitting-forms-with-multiple-buttons
        $form = $this->createForm(RestaurantCostType::class, $restaurantCost);
        // ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See http://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($this->get('slugger')->slugify($post->getTitle()));

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($restaurantCost);
            $entityManager->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See http://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'restaurant.created_successfully');

            /* if ($form->get('saveAndCreateNew')->isClicked()) {
              return $this->redirectToRoute('admin_events');
              } */

            return $this->redirectToRoute('restaurant_meal_show', ['id' => $meal->getId()]);
        }

        return $this->render('admin/restaurant/ticket/edit.html.twig', [
                    'restaurantcost' => $restaurantCost,
                    'form' => $form->createView(),
        ]);
    }
}
