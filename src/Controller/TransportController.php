<?php


namespace App\Controller;

use App\Entity\Transport;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller used to manage users.
 *
 * @author Andrea Putzu <mister.tzu@gmail.com>
 */
class TransportController extends AbstractController
{
    /**
    * @Security("is_granted('ROLE_ADMIN')")
    * @Route("/admin/transport/allocationmap/{id}", requirements={"id": "\d+"}, name="admin_transport_allocation_map", methods={"POST", "GET"})
    */
    public function allocationMapAction($id) {
        
        $url = $this->generateUrl(
                        'transport_search_allocation_map',
                        array('event' => $id));
        
        return $this->render('admin/transport/allocationmap.html.twig', ['event' => $id,
            'url' => $url]);
    }
    
    /**
    * @Security("is_granted('ROLE_ADMIN')")
    * @Route("/admin/transport/searchallocation/{event}", requirements={"event": "\d+"}, name="transport_search_allocation_map", methods={"POST", "GET"})
    */
    public function searchAllocationMapRequest($event)
    {
        $data = $this->getDoctrine()->getRepository(Transport::class)->findAllocationMap($event);
        
        $result[] = array();
        
        foreach ($data as $map) {
            $transportId = $map['tid'];
            if (!isset($result[$transportId])) {
                //$url = $this->generateUrl('admin_restaurantreal_show',array('id' => $transportId));
                $result[$transportId] = array();
                //$result[$transportId]['url'] = $url;
                $result[$transportId]['name'] = $map['tname'];
                $result[$transportId]['buses'] = array();
            }
            
            $busId = $map['bid'];
            $busName = $map['bname'];
            
            if (!isset($result[$transportId]['buses'][$busId])) {
                $result[$transportId]['buses'][$busId] = array();
                $result[$transportId]['buses'][$busId]['name'] = $busName;
                $result[$transportId]['buses'][$busId]['price'] = $map['price'];
                $result[$transportId]['buses'][$busId]['reserved'] = 0;
                $result[$transportId]['buses'][$busId]['tasks'] = array();
            }
            
            $taskID = $map['task'];
            if (!isset($result[$transportId]['buses'][$busId]['tasks'][$taskID])) {
                $url = $this->generateUrl('task_show',array('id' => $taskID));
                $result[$transportId]['buses'][$busId]['tasks'][$taskID] = array();
                $result[$transportId]['buses'][$busId]['tasks'][$taskID]['url'] = $url;
                $result[$transportId]['buses'][$busId]['tasks'][$taskID]['citizens'] = array();
                $result[$transportId]['buses'][$busId]['tasks'][$taskID]['reserved'] = 0;
            }
            
            $birthDate = \DateTime::createFromFormat('Y-m-d H:i:s', $map['birth_date']);
            $age = -1;
            if ($birthDate) {
                $age = $birthDate->diff(new \DateTime('now'))->y;
            }

            $reserved = $result[$transportId]['buses'][$busId]['reserved'] + 1;
            $result[$transportId]['buses'][$busId]['reserved'] = $reserved;
            
            $result[$transportId]['buses'][$busId]['tasks'][$taskID]['reserved'] += 1;
            
            $result[$transportId]['buses'][$busId]['tasks'][$taskID]['citizens'][] = array('id' => $map['cid'],
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
