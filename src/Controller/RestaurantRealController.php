<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\RestaurantExtraCost;
use App\Entity\RestaurantMatch;
use App\Entity\RestaurantMeal;
use App\Entity\RestaurantReal;
use App\Entity\RestaurantRealMeal;
use App\Entity\RestaurantRealMealPrice;
use App\Form\Type\RestaurantExtraCostType;
use App\Form\Type\RestaurantRealType;
use App\Form\Type\RestaurantRealMealPriceType;
use App\Form\Type\RestaurantRealMealType;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantRealController extends AbstractController {

    public function __construct(private ManagerRegistry $doctrine) {}

    /**
     * @Route("/admin/restaurantreal/list/{id}", requirements={"id": "\d+"}, name="admin_restaurant_real_index", methods={"GET"})
     * @Cache(smaxage="10")
     */
    public function indexAction($id) {
        $restaurants = $this->doctrine->getRepository(RestaurantReal::class)->findByEvent($id);
        return $this->render('admin/restaurantreal/index.html.twig', ['restaurants' => $restaurants, 'id' => $id]);
    }

    /**
     * Creates a new Post entity.
     *
     * @Route("/admin/restaurantreal/new/{id}", requirements={"id": "\d+"}, name="admin_restaurantreal_new", methods={"POST", "GET"})
     *
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function newAction($id, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $restaurant = new RestaurantReal();
        $restaurant->setEvent($id);

        return $this->form($restaurant, $request);
    }

    /**
     * Creates a new Post entity.
     *
     * @Route("/admin/restaurantreal/{id}", requirements={"id": "\d+"}, name="admin_restaurantreal_show", methods={"POST", "GET"})
     *
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function showAction(RestaurantReal $restaurant, Request $request) {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(RestaurantRealMealType::class, new RestaurantRealMeal(),
            array('action' => $this->generateUrl('admin_restaurantrealmeal_new', ['id' => $restaurant->getId()])));

        $formextra = $this->createForm(RestaurantExtraCostType::class, new RestaurantExtraCost(),
            array('action' => $this->generateUrl('admin_restaurantextracost_new', ['id' => $restaurant->getId()])));

        $formprice = $this->createForm(RestaurantRealMealPriceType::class, new RestaurantRealMealPrice(),
                array('action' => $this->generateUrl('admin_roomrealprice_new', ['id' => $restaurant->getId()])));

        
        return $this->render('admin/restaurantreal/show.html.twig',
                ['restaurant' => $restaurant,
                  'form' => $form->createView(),
                 'formextra' => $formextra->createView(),
                 'formprice' => $formprice->createView()
                 ]
                 
                );

    }

    /**
     * Creates a new Post entity.
     *
     * @Route("/admin/restaurantreal/edit/{id}", requirements={"id": "\d+"}, name="admin_restaurantreal_edit", methods={"POST", "GET"})
     *
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function editAction(RestaurantReal $restaurant, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->form($restaurant, $request);

    }

    public function form(RestaurantReal $restaurant, Request $request) {
        $restaurantVirtual = $restaurant->getRestaurant();
        $restaurantVirtuals = $this->doctrine->getRepository(Restaurant::class)->findAll();

        $form = $this->createForm(RestaurantRealType::class, $restaurant, array('restaurants' => $restaurantVirtuals));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($this->get('slugger')->slugify($post->getTitle()));

            // Restaurant INIT
            $data = $form['restaurants']->getData();

            $restaurant->setRestaurant($data);

            // Restaurant END

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($restaurant);
            $entityManager->flush();

            $this->addFlash('success', 'restaurant.created_successfully');

            /* if ($form->get('saveAndCreateNew')->isClicked()) {
              return $this->redirectToRoute('admin_events');
              } */

            return $this->redirectToRoute('admin_restaurantreal_show', ['id' => $restaurant->getId()]);
        }

        return $this->render('admin/restaurantreal/new.html.twig', [
                    'restaurant' => $restaurant,
                    'form' => $form->createView(),
                    'h' => empty($restaurantVirtual) ? 0 : $restaurantVirtual->getId(),
        ]);
    }

    /**
     * @Route("/admin/restaurantreal/search/{restaurant}", requirements={"restaurant": "\d+"}, name="restaurantreal_handle_search", methods={"POST", "GET"})
     */
    public function searchRequest(Request $request, $restaurant) {
        $em = $this->doctrine->getManager();
        $restaurantfree = $em->getRepository(RestaurantRealMeal::class)->findFreeByRestaurant($restaurant);
        $result = array();

        foreach ($restaurantfree as $restaurant) {
            $restaurantId = $restaurant['restaurant_real'];

            if (!isset($result[$restaurantId])) {
                $result[$restaurantId] = array('meals' => array(),
                    'name' => $restaurant['rname'] . ' ' . $restaurant['rsurname']);
            }
            
            if (!isset($result[$restaurantId]['meals'][$restaurant['id']])) {
                $restaurant['guests'] -=  $restaurant['rcount'];
                $r = array('id' => $restaurant['id'],
                    'name' => $restaurant['name'],
                    'guests' => $restaurant['guests'],
                    'assigned' => $restaurant['rcount'],
                    'html' => $this->renderView('admin/restaurantrealmeal/_row.html.twig', array('meal' => $restaurant, 'show_control' => 0)));
                $result[$restaurantId]['meals'][$restaurant['id']] = $r;
                //array_push($result[$restaurantId]['meals'][$restaurant['id']], $r);
            }
        }

        $data =  json_encode($result);
        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Route("/admin/restaurantreal/match/{id}", requirements={"id": "\d+"}, name="admin_restaurant_match", methods={"POST", "GET"})
     */
    public function matchAction($id, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $restaurants = $this->doctrine->getRepository(Restaurant::class)->findByEvent($id);

        $postData = $request->request->get('match');
        $name_value = $postData['restaurant'];
        $mealid = $postData['restaurantmeal'];
        
        $urltask = "";
        $urlrestaurant = "";
        $meals = array();

        if(empty($mealid)) {
            $mealid = 0;
        }
            
        if(!empty($name_value)) {
            $meals = $this->doctrine->getRepository(RestaurantMeal::class)->findByRestaurant($name_value);
            
            $urltask = $this->generateUrl(
                        'task_handle_search_restaurant',
                        array('event' => $id, 
                            'restaurant' => $name_value,
                            'meal' => $mealid));
            $urlrestaurant = $this->generateUrl(
                        'restaurantreal_handle_search',
                        array('restaurant' => $name_value));
        } else {
            $name_value = 0;
        }
        //var_dump(empty($name_value)); die();

        //$form = $this->createForm(MatchType::class, new Restaurant(), array('restaurants' => $restaurants));
        //$form->handleRequest($request);

        return $this->render('admin/restaurantreal/match.html.twig', ['event' => $id,
            'restaurants' => $restaurants, 
            'meals' => $meals,
            'mealid' => $mealid,
            'value' => $name_value,
            'urlt' => $urltask, 
            'urlr' => $urlrestaurant]);
    }


    /**
     * @Route("/admin/restaurantreal/allocation", name="admin_restaurant_allocation", methods={"POST", "GET"})
     */
    public function allocationAction(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $m = $request->request->get('m');
        $r = $request->request->get('r');
        $v = $request->request->get('v');
        
        $result = array();
        $user = $this->getUser();

        if (empty($m) || empty($r) || empty($v)) {
            $result['status'] = 'ERROR';
            return $this->jsonReturn($result);
        }

        $entityManager = $this->doctrine->getManager();
        foreach ($m as $restaurantcost) {
            foreach ($r as $restaurantMealReal) {
                $h = new RestaurantMatch();
                $h->setRestaurantcost($restaurantcost);
                $h->setRestaurant($v);
                $h->setMealreal($restaurantMealReal);
                $h->setD(0);
                $h->setLast(new \DateTime());
                $h->setUid($user->getId());
                $entityManager->persist($h);
                $entityManager->flush();
            }
        }



        $result['status'] = 'OK';
        return $this->jsonReturn($result);
    }

    /**
     * @Route("/admin/restaurantreal/allocation/delete", name="admin_restaurant_delete_allocation", methods={"POST", "GET"})
     */
    public function deleteAllocationAction(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $m = $request->request->get('m');
        $user = $this->getUser();

        $result = array();

        if (empty($m)) {
            $result['status'] = 'ERROR';
            return $this->jsonReturn($result);
        }
        $em = $this->doctrine->getManager();

        foreach ($m as $matchid) {
                $h = $em->getRepository(RestaurantMatch::class)->find($matchid);
                $h->setD(1);
                $h->setLast(new \DateTime());
                $h->setUid($user->getId());
                $em->persist($h);
                $em->flush();
        }



        $result['status'] = 'OK';
        return $this->jsonReturn($result);
    }

    private function jsonReturn($result) {
        $data =  json_encode($result);
        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Route("/admin/restaurantreal/matched/{id}", requirements={"id": "\d+"}, name="admin_restaurant_matched", methods={"POST", "GET"})
     */
    public function matchedAction($id, Request $request) {
        $restaurants = $this->doctrine->getRepository(Restaurant::class)->findByEvent($id);

        $postData = $request->request->get('match');
        $name_value = $postData['restaurant'];

        $urltask = "";
        
        if(!empty($name_value)) {
            $urltask = $this->generateUrl(
                        'task_handle_search_restaurant_allocation',
                        array('event' => $id, 'restaurant' => $name_value));
        } else {
            $name_value = 0;
        }
        //var_dump(empty($name_value)); die();

        //$form = $this->createForm(MatchType::class, new Restaurant(), array('restaurants' => $restaurants));
        //$form->handleRequest($request);

        return $this->render('admin/restaurantreal/matched.html.twig', ['event' => $id,
            'restaurants' => $restaurants, 
            'value' => $name_value,
            'urlt' => $urltask]);
    }
    
    /**
     * @Route("/admin/restaurantreal/allocationmap/{id}", requirements={"id": "\d+"}, name="admin_restaurant_allocation_map", methods={"POST", "GET"})
     */
    public function allocationMapAction($id) {
        
        $url = $this->generateUrl(
                        'restaurant_search_allocation_map',
                        array('event' => $id));
        
        return $this->render('admin/restaurantreal/allocationmap.html.twig', ['event' => $id,
            'url' => $url]);
    }
    
    /**
     * @Route("/admin/restaurantreal/searchallocation/{event}", requirements={"event": "\d+"}, name="restaurant_search_allocation_map", methods={"POST", "GET"})
     */
    public function searchAllocationMapRequest($event)
    {
        $data = $this->doctrine->getRepository(RestaurantReal::class)->findAllocationMap($event);
        
        $result[] = array();
        
        foreach ($data as $map) {
            $restaurantId = $map['rrid'];
            if (!isset($result[$restaurantId])) {
                $url = $this->generateUrl('admin_restaurantreal_show',array('id' => $restaurantId));
                $result[$restaurantId] = array();
                $result[$restaurantId]['url'] = $url;
                $result[$restaurantId]['name'] = $map['rrname'] . ' ' . $map['rrsurname'];
                $result[$restaurantId]['meals'] = array();
            }
            
            $mealId = $map['rrmid'];
            $mealRName = $map['rrmname'];
            
            if (!isset($result[$restaurantId]['meals'][$mealId])) {
                $result[$restaurantId]['meals'][$mealId] = array();
                $result[$restaurantId]['meals'][$mealId]['name'] = $mealRName;
                $result[$restaurantId]['meals'][$mealId]['price'] = $map['selling_price'];
                $result[$restaurantId]['meals'][$mealId]['cost'] = $map['supplier_cost'];
                $result[$restaurantId]['meals'][$mealId]['reserved'] = 0;
                //$result[$hotelId]['rooms'][$roomId]['rname'] = $map['rname'];
                $result[$restaurantId]['meals'][$mealId]['tasks'] = array();
            }
            
            $taskID = $map['task'];
            if (!isset($result[$restaurantId]['meals'][$mealId]['tasks'][$taskID])) {
                $url = $this->generateUrl('task_show',array('id' => $taskID));
                $result[$restaurantId]['meals'][$mealId]['tasks'][$taskID] = array();
                $result[$restaurantId]['meals'][$mealId]['tasks'][$taskID]['url'] = $url;
                $result[$restaurantId]['meals'][$mealId]['tasks'][$taskID]['citizens'] = array();
            }
            
            $birthDate = \DateTime::createFromFormat('Y-m-d H:i:s', $map['birth_date']);
            $age = -1;
            if ($birthDate) {
                $age = $birthDate->diff(new \DateTime('now'))->y;
            }
            $reserved = $result[$restaurantId]['meals'][$mealId]['reserved'] + 1;
            $result[$restaurantId]['meals'][$mealId]['reserved'] = $reserved;
            
            $result[$restaurantId]['meals'][$mealId]['tasks'][$taskID]['citizens'][] = array('id' => $map['cid'],
                        'name' => $map['name'],
                        'surname' => $map['surname'],
                        'age' => $age,
                    );
        }
        
        unset($result[0]);
        //var_dump($result); die();
        return new JsonResponse(json_encode($result), 200, [], true);
    }
}
