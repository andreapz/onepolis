<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Citizen;

use App\Form\Type\AddressType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends AbstractController {
    
    /**
     * Displays a form to edit an existing Task entity.
     *
     *
     * @Route("/admin/address/citizen/{id}/add", requirements={"id": "\d+"}, name="address_citizen_add", methods={"POST", "GET"})
     */
    public function addCitizenAction(Citizen $citizen, Request $request) {
        //$this->denyAccessUnlessGranted('edit', $post, 'Posts can only be edited by their authors.');
//@Security("is_granted('ROLE_ADMIN')")
        
        $address = new Address();
        
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($this->get('slugger')->slugify($post->getTitle()));
            
            //$task->addCitizen($citizen);
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($address);
            $entityManager->flush();
            
            $citizen->setAddress($address);
            $entityManager->persist($citizen);
            $entityManager->flush();
            
            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See http://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'address.created_successfully');

            /* if ($form->get('saveAndCreateNew')->isClicked()) {
              return $this->redirectToRoute('admin_events');
              } */

            return $this->redirectToRoute('task_show', ['id' => $citizen->getTask()->getId()]);
        }

        return $this->render('admin/address/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     *
     * @Route("/admin/address/citizen/{id}/{aid}/add", requirements={"id": "\d+"}, name="address_citizen_add_existent", methods={"POST", "GET"})
     */
    public function addCitizenExistentAction(Citizen $citizen, int $addressId, Request $request) {
        //$this->denyAccessUnlessGranted('edit', $post, 'Posts can only be edited by their authors.');
//@Security("is_granted('ROLE_ADMIN')")
        
        $repository = $this->doctrine->getRepository(Address::class);
        $addressCitizen = $repository->find($addressId);
        
        $address = new Address();
        $address->copy($addressCitizen);
        
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($this->get('slugger')->slugify($post->getTitle()));
            
            //$task->addCitizen($citizen);
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($address);
            $entityManager->flush();
            
            $citizen->setAddress($address);
            $entityManager->persist($citizen);
            $entityManager->flush();
            
            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See http://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'address.created_successfully');

            /* if ($form->get('saveAndCreateNew')->isClicked()) {
              return $this->redirectToRoute('admin_events');
              } */

            return $this->redirectToRoute('task_show', ['id' => $citizen->getTask()->getId()]);
        }

        return $this->render('admin/address/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * Displays a form to edit an existing Task entity.
     *
     *
     * @Route("/admin/address/citizen/{id}/edit", requirements={"id": "\d+"}, name="address_citizen_edit", methods={"POST", "GET"})
     */
    public function editCitizenAction(Citizen $citizen, Request $request) {
        //$this->denyAccessUnlessGranted('edit', $post, 'Posts can only be edited by their authors.');
//@Security("is_granted('ROLE_ADMIN')")
        
        $repository = $this->doctrine->getRepository(Address::class);
        $address = $repository->find($citizen->getAddress());
        
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($this->get('slugger')->slugify($post->getTitle()));
            
            //$task->addCitizen($citizen);
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($address);
            $entityManager->flush();
            
            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See http://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'address.created_successfully');

            /* if ($form->get('saveAndCreateNew')->isClicked()) {
              return $this->redirectToRoute('admin_events');
              } */

            return $this->redirectToRoute('task_show', ['id' => $citizen->getTask()->getId()]);
        }

        return $this->render('admin/address/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}