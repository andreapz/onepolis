<?php

namespace App\Controller;

use App\Entity\RestaurantReal;
use App\Entity\RestaurantRealMeal;
use App\Entity\RestaurantRealMealPrice;
use App\Form\Type\RestaurantRealMealPriceType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantRealMealPriceController extends AbstractController {
    
    public function __construct(private ManagerRegistry $doctrine) {}

    /**
     * @Route("/admin/restaurantrealmealprice/list/{id}", requirements={"id": "\d+"}, name="restaurantrealmealprice_index", methods={"GET"})
     * @Cache(smaxage="10")
     */
    public function indexAction($id) {
        $meals = $this->doctrine->getRepository(RestaurantRealMealPrice::class)->findAll();
        return $this->render('restaurantrealmealprice/index.html.twig', ['meals' => $meals]);
    }
    
    /**
    * Creates a new Post entity.
    *
    * @Route("/admin/restaurantrealmealprice/new/{id}", requirements={"id": "\d+"}, name="admin_restaurantrealmealprice_new", methods={"POST", "GET"})
    *
    * 
    * NOTE: the Method annotation is optional, but it's a recommended practice
    * to constraint the HTTP methods each controller responds to (by default
    * it responds to all methods).
    */
    public function newAction(RestaurantRealMeal $meal, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $mealprice = new RestaurantRealMealPrice();
        $mealprice->setMeal($meal);

        return $this->form($mealprice, $request);
    }    

   /**
    * Creates a new Post entity.
    *
    * @Route("/admin/restaurantrealmealprice/{id}", requirements={"id": "\d+"}, name="admin_restaurantrealmealprice_show", methods={"POST", "GET"})
    *
    */
    public function showAction(RestaurantRealMealPrice $extra, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/restaurantrealmealprice/show.html.twig', ['extra' => $extra]);
    }   

    /**
     * Creates a new Post entity.
     *
     * @Route("/admin/restaurantrealmealprice/edit/{id}", requirements={"id": "\d+"}, name="admin_restaurantrealmealprice_edit", methods={"POST", "GET"})
     *
     */
    public function editAction(RestaurantRealMealPrice $mealprice, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->form($mealprice, $request);
    }    

    public function form(RestaurantRealMealPrice $mealprice, Request $request) {
        $form = $this->createForm(RestaurantRealMealPriceType::class, $mealprice);
        $form->handleRequest($request);
        $result = array();
        $result['status'] = 'Waiting'; 
        
        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($this->get('slugger')->slugify($post->getTitle()));

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($mealprice);
            $entityManager->flush();

            //return $this->redirectToRoute('admin_meal_real_index', ['id' => $meal->getEvent()]);
            $result['status'] = 'OK';
            $result['mealprice']['id'] = $mealprice->getId();
            $result['html'] = $this->renderView('admin/restaurantrealmealprice/_row.html.twig', array('mealprice' => $mealprice));
        }

       $data =  json_encode($result);
       return new JsonResponse($data, 200, [], true);
    }
        
    /**
    * Delete a new Post entity.
    *
    * @Route("/admin/restaurantrealmealprice/delete/{id}", requirements={"id": "\d+"}, name="admin_restaurantrealmealprice_delete", methods={"POST", "GET"})
    *
    */
    public function deleteAction(RestaurantRealMealPrice $mealprice) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $result = array();
        $result['status'] = 'ERROR'; 
        $mealprice->setMeal(null);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($mealprice);
        $entityManager->flush();
        $result['status'] = 'OK';
        $result['$mealprice']['id'] = $mealprice->getId();
        $data =  json_encode($result);
        return new JsonResponse($data, 200, [], true);
    }  
}