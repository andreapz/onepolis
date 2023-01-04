<?php

namespace App\Controller;

use App\Entity\RestaurantReal;
use App\Entity\RestaurantExtraCost;
use App\Form\Type\RestaurantExtraCostType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantExtraCostController extends AbstractController {
    
    /**
     * @Route("/admin/restaurantextracost/list/{id}", requirements={"id": "\d+"}, name="restaurantextracost_index", methods={"GET"})
     * @Cache(smaxage="10")
     */
    public function indexAction($id) {
        $rooms = $this->doctrine->getRepository(RestaurantExtraCost::class)->findAll();
        // Every template name also has two extensions that specify the format and
        // engine for that template.
        // See https://symfony.com/doc/current/templating.html#template-suffix
        return $this->render('restaurantextracost/index.html.twig', ['rooms' => $rooms]);
    }
    
    /**
    * Creates a new Post entity.
    *
    * @Route("/admin/restaurantextracost/new/{id}", requirements={"id": "\d+"}, name="admin_restaurantextracost_new", methods={"POST", "GET"})
    *
    * 
    * NOTE: the Method annotation is optional, but it's a recommended practice
    * to constraint the HTTP methods each controller responds to (by default
    * it responds to all methods).
    */
    public function newAction(RestaurantReal $restaurant, Request $request) {
       $this->denyAccessUnlessGranted('ROLE_ADMIN');  
        $extra = new RestaurantExtraCost();
        $extra->setRestaurant($restaurant);

        return $this->form($extra, $request);
    }    

   /**
    * Creates a new Post entity.
    *
    * @Route("/admin/restaurantextracost/{id}", requirements={"id": "\d+"}, name="admin_restaurantextracost_show", methods={"POST", "GET"})
    *
    * 
    * NOTE: the Method annotation is optional, but it's a recommended practice
    * to constraint the HTTP methods each controller responds to (by default
    * it responds to all methods).
    */
    public function showAction(RestaurantExtraCost $extra, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
       return $this->render('admin/restaurantextracost/show.html.twig', ['extra' => $extra]);
    }   

    /**
     * Creates a new Post entity.
     *
     * @Route("/admin/restaurantextracost/edit/{id}", requirements={"id": "\d+"}, name="admin_restaurantextracost_edit", methods={"POST", "GET"})
     *
     * 
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function editAction(RestaurantExtraCost $extra, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->form($extra, $request);

    }    

    public function form(RestaurantExtraCost $extra, Request $request) {
        $form = $this->createForm(RestaurantExtraCostType::class, $extra);
        $form->handleRequest($request);
        $result = array();
        $result['status'] = 'Waiting'; 
       
        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($this->get('slugger')->slugify($post->getTitle()));

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($extra);
            $entityManager->flush();

            
            $result['status'] = 'OK';
            $result['extra']['id'] = $extra->getId();
            $result['html'] = $this->renderView('admin/restaurantextracost/_row.html.twig', array('restaurantextracost' => $extra));
        }

       $data =  json_encode($result);
       return new JsonResponse($data, 200, [], true);
    }
        
    /**
    * Delete a new Post entity.
    *
    * @Route("/admin/restaurantextracost/delete/{id}", requirements={"id": "\d+"}, name="admin_restaurantextracost_delete", methods={"POST", "GET"}) 
    */
    public function deleteAction(RestaurantExtraCost $extra) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $result = array();
        $result['status'] = 'ERROR'; 
        $extra->setRestaurant(null);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($extra);
        $entityManager->flush();
        $result['status'] = 'OK';
        $result['extra']['id'] = $extra->getId();
        $data =  json_encode($result);
        return new JsonResponse($data, 200, [], true);
    }  
}