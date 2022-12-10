<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\CitizenPayment;
use App\Entity\Event;
use App\Entity\Task;
use App\Entity\TicketCost;
use App\Form\Type\EventType;
use App\Form\Type\CitizenPaymentType;
use App\Utils\UUID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Forms;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EventController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine) {}

    
    /**
     * @Route("/", name="homepage")
     * @Route("/event", name="event")
     */
    public function index()
    {
        //$events = $this->doctrine->getRepository(Event::class)->findAll(); 
        //return $this->render('event/index.html.twig', ['events' => $events]);
        return $this->redirectToRoute('event_show', ['ueid' => '1546058f-5a25-4334-85ae-e68f2a44bbaf']);
    }
    
    /**
     * Finds and displays a Event entity.
     *
     * @Route("/event/{ueid}", name="event_show", methods={"GET"})
     */
    public function showAction($ueid) {
        $event = $this->getEvent($this, $this->doctrine, $ueid);
        
        $interval = $event->getInitialDate()->diff($event->getEndDate());
        $user = $this->getUser();
        $tasks = array();
        
        if(!is_null($user)) {
            $tasks = $this->doctrine
                    ->getRepository(Task::class)
                    ->findAllByEventAndUser($event->getId(), $user->getId());
        }
        
        $form = $this->createForm(CitizenPaymentType::class, new CitizenPayment(), 
                array('action' => $this->generateUrl('admin_citizen_new_payment')));
        
        return $this->render('admin/event/show.html.twig', [
                    'event' => $event,
                    'nights' => $interval->format('%a'),
                    'tasks' => $tasks,
                    'form' => $form->createView(),
        ]);
    }
    
    public static function getEvent($controller, $doctrine, $ueid) {
        if (!UUID::is_valid($ueid)) {
            throw $controller->createNotFoundException('The event does not exist');
        }
        
        $event = $doctrine
                    ->getRepository(Event::class)
                    ->findOneByUeid($ueid);
        
        if ($event == null) {
            throw $controller->createNotFoundException('The event does not exist');
        }
        
        return $event;
    }

    /**
     * Creates a new Event entity.
     *
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/admin/event/new", name="admin_event_new", methods={"POST", "GET"})
     */
    public function newAction(Request $request) {
        $event = new Event();
        $address = new Address();
        $event->addAddress($address);
        
        //$post->setAuthor($this->getUser());
        //* @Security("is_granted('ROLE_ADMIN')")
        $form = $this->createForm(EventType::class, $event);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($event);
            $event->setUeid(UUID::v4());
            $address->setEvent($event);
            $entityManager->flush();

            return $this->redirectToRoute('admin_events');
        }

        return $this->render('admin/event/new.html.twig', [
                    'post' => $event,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Event entity.
     *
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/admin/event/{ueid}/edit", requirements={"id": "\d+"}, name="admin_event_edit", methods={"POST", "GET"})
     */
    public function editAction(Request $request, $ueid) {
        $event = $this->getEvent($this, $this->doctrine, $ueid);
        
        $entityManager = $this->doctrine->getManager();
        $originalTickets = new ArrayCollection();

        foreach ($event->getTickets() as $ticket) {
            $originalTickets->add($ticket);
        }

        $form = $this->createForm(EventType::class, $event);    

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($event->getTickets() as $ticket) {
                $ticket->setEvent($event);
            }

            foreach ($originalTickets as $ticket) {
                if (false === $event->getTickets()->contains($ticket)) {
                    $ticket->setEvent(null);
                    $entityManager->remove($ticket);
                    $entityManager->persist($ticket);
                }
            }

            $entityManager->flush();

            $this->addFlash('success', 'post.updated_successfully');

            return $this->redirectToRoute('admin_event_edit', ['id' => $event->getId()]);
        }

        return $this->render('admin/event/edit.html.twig', [
                    'event' => $event,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/admin/event/create")
     */
    public function createAction() {
        $event = new Event();
        $event->setTitle('Mariapoli2018');

        $em = $this->doctrine->getManager();
        $em->persist($event);
        $em->flush();

        return new Response('Saved new product with id ' . $event->getId());
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/admin/events", name="admin_events", methods={"GET"})
     */
    public function eventsAction() {
        $events = $this->doctrine
                ->getRepository('App:Event')
                ->findAll();

        return $this->render('admin/event/events.html.twig', [
                    'events' => $events,
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/admin/event/update/{ueid}")
     */
    public function updateAction($ueid) {
        if (!UUID::is_valid($ueid)) {
            throw $this->createNotFoundException('The EVENT does not exist');
        }
        
        $event = $this->doctrine
                    ->getRepository(Event::class)
                    ->findOneByUeid($ueid);
        
        if ($event == null) {
            throw $this->createNotFoundException('The EVENT does not exist');
        }
        
        $em = $this->doctrine->getManager();
        //$event = $em->getRepository('AppBundle:Event')->find($eventId);

        if (!$event) {
            throw $this->createNotFoundException(
                    'No product found for id ' . $eventId
            );
        }

        $event->setTitle('Mariapoli2019');
        $em->flush();

        return $this->redirectToRoute('showAll');
    }
    
    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/admin/event/report/{ueid}", requirements={"id": "\d+"}, name="event_report_map", methods={"POST", "GET"})
     */
    public function reportRequest($ueid)
    {
        if (!UUID::is_valid($ueid)) {
            throw $this->createNotFoundException('The EVENT does not exist');
        }
        
        $event = $this->doctrine
                    ->getRepository(Event::class)
                    ->findOneByUeid($ueid);
        
        if ($event == null) {
            throw $this->createNotFoundException('The EVENT does not exist');
        }
        
        $prices = $this->doctrine
                ->getRepository(TicketCost::class)
                ->findPrices($event->getId());
        
        $result[] = array();
        //$result['prices'] = array();
        $value = 0.0;
        
        foreach ($prices as $price) {
            $value += floatval ( $price['price']);
            //$result['prices'][] = array('price' => $price['price'] );
        }
        $result['value'] = $value;
        

        unset($result[0]);
        //var_dump($result); die();
        return new JsonResponse(json_encode($result), 200, [], true);
    }

     /**
     * @Route("/react", name="react-test")
     */
    public function react()
    {

        return $this->render('react.html.twig', [
            'packagesData' => array()
]);
    }
}
