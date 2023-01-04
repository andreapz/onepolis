<?php

namespace App\Controller;

use App\Entity\RestaurantReal;
use App\Entity\RestaurantRealMeal;
use App\Form\Type\RestaurantRealMealType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantRealMealController extends AbstractController {
    
    /**
     * @Route("/admin/restaurantrealmeal/list/{id}", requirements={"id": "\d+"}, name="restaurantrealmeal_index", methods={"GET"})
     * @Cache(smaxage="10")
     */
    public function indexAction($id) {
        $rooms = $this->doctrine->getRepository(RestaurantRealMeal::class)->findAll();
        // Every template name also has two extensions that specify the format and
        // engine for that template.
        // See https://symfony.com/doc/current/templating.html#template-suffix
        return $this->render('restaurantrealmeal/index.html.twig', ['rooms' => $rooms]);
    }
    
    /**
    * Creates a new Post entity.
    *
    * @Route("/admin/restaurantrealmeal/new/{id}", requirements={"id": "\d+"}, name="admin_restaurantrealmeal_new", methods={"POST", "GET"})
    *
    * 
    * NOTE: the Method annotation is optional, but it's a recommended practice
    * to constraint the HTTP methods each controller responds to (by default
    * it responds to all methods).
    */
    public function newAction(RestaurantReal $restaurant, Request $request) {
       $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $meal = new RestaurantRealMeal();
        $meal->setRestaurant($restaurant);
        $meal->setRid($restaurant->getRestaurant()->getId());
        
        return $this->form($meal, $request);
    }    

   /**
    * Creates a new Post entity.
    *
    * @Route("/admin/restaurantrealmeal/{id}", requirements={"id": "\d+"}, name="admin_restaurantrealmeal_show", methods={"POST", "GET"})
    *
    * 
    * NOTE: the Method annotation is optional, but it's a recommended practice
    * to constraint the HTTP methods each controller responds to (by default
    * it responds to all methods).
    */
    public function showAction(RestaurantRealMeal $room, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/restaurantrealmeal/show.html.twig', ['room' => $room]);
    }   

    /**
     * Creates a new Post entity.
     *
     * @Route("/admin/restaurantrealmeal/edit/{id}", requirements={"id": "\d+"}, name="admin_restaurantrealmeal_edit", methods={"POST", "GET"})
     *
     * 
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function editAction(RestaurantRealMeal $room, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->form($room, $request);

    }    

    public function form(RestaurantRealMeal $meal, Request $request) {
        $form = $this->createForm(RestaurantRealMealType::class, $meal);
        $form->handleRequest($request);
        $result = array();
        $result['status'] = 'Waiting'; 

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($meal);
            $entityManager->flush();

            $result['status'] = 'OK';
            $result['room']['id'] = $meal->getId();
            $result['html'] = $this->renderView('admin/restaurantrealmeal/_row.html.twig', array('meal' => $meal, 'show_control' => 1));
        }

        $data =  json_encode($result);
        return new JsonResponse($data, 200, [], true);
    }
        
    /**
    * Delete a new Post entity.
    *
    * @Route("/admin/restaurantrealmeal/delete/{id}", requirements={"id": "\d+"}, name="admin_restaurantrealmeal_delete", methods={"POST", "GET"})
    * 
    * NOTE: the Method annotation is optional, but it's a recommended practice
    * to constraint the HTTP methods each controller responds to (by default
    * it responds to all methods).
    */
    public function deleteAction(RestaurantRealMeal $room) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $result = array();
        $result['status'] = 'ERROR'; 
        $room->setRestaurant(null);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($room);
        $entityManager->flush();
        $result['status'] = 'OK';
        $result['room']['id'] = $room->getId();
        $data =  json_encode($result);
        return new JsonResponse($data, 200, [], true);
    }  
}